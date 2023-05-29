<?php

namespace NormanHuth\NovaPerspectives;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;

class PerspectiveHelper
{
    /**
     * The perspectives Config Class.
     *
     * @var string|null
     */
    protected static ?string $class = null;

    /**
     * Get the perspectives Config Class.
     *
     * @return string|null
     */
    public static function getClass(): ?string
    {
        if (!static::$class) {
            static::$class = config('nova-perspectives.config-class');
        }

        return static::$class;
    }

    /**
     * @throws ReflectionException
     */
    public static function options(?Request $request = null): array
    {
        if (!$request) {
            $request = app(NovaRequest::class);
        }

        return static::getPerspectives($request)->mapWithKeys(function (array $perspective) {
            return [$perspective['slug'] => $perspective['label']];
        })->toArray();
    }

    /**
     * Get configured perspectives.
     *
     * @param Request $request
     * @throws ReflectionException
     * @return Collection
     */
    public static function getPerspectives(Request $request): Collection
    {
        $perspectives = [];
        $namespace = app()->getNamespace();
        $directory = config('nova-perspectives.directory');

        foreach ((new Finder())->in($directory)->files() as $perspective) {
            $perspective = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($perspective->getPathname(), app_path().DIRECTORY_SEPARATOR)
                );

            if (
                is_subclass_of($perspective, Perspective::class) &&
                ! (new ReflectionClass($perspective))->isAbstract()
            ) {
                $perspectives[] = $perspective;
            }
        }

        $perspectives = collect($perspectives);
        $i = 0;

        return $perspectives->map(function ($perspective) use ($request) {
            return new $perspective();
        })
            ->sortBy('priority')
            ->filter(function ($perspective) use ($request) {
                return $perspective->authorizedToSee($request);
            })
            ->mapWithKeys(function ($perspective) use ($request, &$i) {
                return [$i++ => $perspective->serialize($request)];
            });
    }

    /**
     * Set a selected perspective.
     *
     * @param string $perspective
     * @return void
     */
    public static function setSelectedPerspective(string $perspective): void
    {
        Cookie::queue(static::getCookieName(), $perspective, 60*60*365);
    }

    /**
     * Get Cookie name for perspectives.
     *
     * @return string
     */
    public static function getCookieName(): string
    {
        return config('nova-perspectives.cookie');
    }

    /**
     * Get the active perspective for the current user.
     *
     * @param Request    $request
     * @param Collection $perspectives
     * @return array|null
     */
    public static function getActivePerspective(Request $request, Collection $perspectives): ?array
    {
        $perspective = null;
        $userPerspective = $request->cookie(static::getCookieName());

        if ($userPerspective) {
            $perspective = $perspectives->where('slug', $userPerspective)->first();
        }

        if (!$perspective) {
            $user = $request->user();
            if (method_exists($user, 'defaultPerspective')) {
                $defaultPerspective = call_user_func([$user, 'defaultPerspective']);
                $perspective = $perspectives->where('slug', $defaultPerspective)->first();
            }
        }

        if (!$perspective) {
            $perspective = $perspectives->first();
        }

        return $perspective;
    }
}
