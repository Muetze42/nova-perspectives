<?php

namespace NormanHuth\NovaPerspectives\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use NormanHuth\NovaPerspectives\Perspective;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class NovaPerspectivesCollection
{
    /**
     * Return Nova Perspectives Collection for authorized user.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @throws \ReflectionException
     * @return \Illuminate\Support\Collection
     */
    public static function create(Request $request): Collection
    {
        $perspectives = [];
        $namespace = app()->getNamespace();
        $directory = config('nova-perspectives.directory');

        foreach ((new Finder())->in($directory)->files() as $perspective) {
            $perspective = $namespace . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($perspective->getPathname(), app_path() . DIRECTORY_SEPARATOR)
            );

            if (
                is_subclass_of($perspective, Perspective::class) &&
                !(new ReflectionClass($perspective))->isAbstract()
            ) {
                $perspectives[] = $perspective;
            }
        }

        $perspectives = collect($perspectives);
        $i = 0;

        return $perspectives
            ->map(function ($perspective) use ($request) {
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
}
