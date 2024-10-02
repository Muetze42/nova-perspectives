<?php

namespace NormanHuth\NovaPerspectives\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Nova;
use NormanHuth\NovaPerspectives\PerspectiveHelper;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;

class HandleNovaPerspektive
{
    /**
     * The current user id.
     *
     * @var int|string|null
     */
    protected int|string|null $userId;

    /**
     * Share only a subset of a perspective from the given array.
     *
     * @var array
     */
    protected array $toShare = [
        'slug',
        'label',
        'icons',
        'classes',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request                      $request
     * @param Closure(Request): (Response) $next
     *
     * @throws ReflectionException
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        $perspectives = PerspectiveHelper::getPerspectives($request);

        if (!$perspectives->count()) {
            return $next($request);
        }

        $perspective = PerspectiveHelper::getActivePerspective($request, $perspectives);

        $this->shareData($request, $perspectives, $perspective);

        $request->query->set('viaPerspective', $perspective['slug']);

        return $next($request);
    }

    protected function shareData(Request $request, Collection $perspectives, array $perspective): void
    {
        $this->resolveMainMenu($request, $perspective);
        $this->resolveUserMenu($request, $perspective);
        $this->resolveUnfilteredMainMenuOver($request, $perspective);
        $this->resolveUnfilteredMainMenuUnder($request, $perspective);

        $value = [
            'prefix' => config('nova-perspectives.class-prefix', 'perspective-'),
            'beKind' => config('nova-perspectives.be-kind', true),
            'redirectAfterSwitch' => config('nova-perspectives.redirect_after_switch'),
            'current' => Arr::only($perspective, $this->toShare),
            'perspectives' => $perspectives
                ->where('slug', '!=', $perspective['slug'])
                ->map(function ($perspective) {
                    return Arr::only($perspective, $this->toShare);
                })->toArray(),
        ];

        Nova::provideToScript([
            'perspectives' => fn () => $value,
        ]);
    }

    /**
     * Resolve the main menu for Nova perspective.
     *
     * @param Request $request
     * @param array   $perspective
     *
     * @return void
     */
    protected function resolveMainMenu(Request $request, array $perspective): void
    {
        if (!empty($perspective['mainMenu']) && is_callable($perspective['mainMenu']) && $this->getUserId()) {
            Menu::wrap(call_user_func($perspective['mainMenu'], $request));
        }
    }

    /**
     * Resolve the user menu for Nova perspective.
     *
     * @param Request $request
     * @param array   $perspective
     *
     * @return void
     */
    protected function resolveUserMenu(Request $request, array $perspective): void
    {
        if (!empty($perspective['userMenu']) && is_callable($perspective['userMenu']) && $this->getUserId()) {
            Menu::wrap(call_user_func($perspective['userMenu'], $request));
        }
    }

    /**
     * Resolve the unfiltered menu over main menu for Nova perspective.
     *
     * @param Request $request
     * @param array   $perspective
     *
     * @return void
     */
    protected function resolveUnfilteredMainMenuOver(Request $request, array $perspective): void
    {
        if (!empty($perspective['unfilteredMainMenuOver']) && is_callable($perspective['unfilteredMainMenuOver']) && $this->getUserId()) {
            Nova::serving(function (ServingNova $event) use ($perspective, $request) {
                Nova::provideToScript([
                    'unfilteredMainMenuOver' => fn () => Menu::wrap(call_user_func($perspective['unfilteredMainMenuOver'], $request)),
                ]);
            });
        }
    }

    /**
     * Resolve the unfiltered menu under main menu for Nova perspective.
     *
     * @param Request $request
     * @param array   $perspective
     *
     * @return void
     */
    protected function resolveUnfilteredMainMenuUnder(Request $request, array $perspective): void
    {
        if (!empty($perspective['unfilteredMainMenuUnder']) && is_callable($perspective['unfilteredMainMenuUnder']) && $this->getUserId()) {
            Nova::serving(function (ServingNova $event) use ($perspective, $request) {
                Nova::provideToScript([
                    'unfilteredMainMenuUnder' => fn () => Menu::wrap(call_user_func($perspective['unfilteredMainMenuUnder'], $request)),
                ]);
            });
        }
    }

    /**
     * Get the current user id.
     *
     * @return int|string|null
     */
    protected function getUserId(): int|string|null
    {
        if (!isset($this->userId)) {
            $this->userId = Auth::guard(config('nova.guard'))->id() ?? null;
        }

        return $this->userId;
    }
}
