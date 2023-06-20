<?php

namespace NormanHuth\NovaPerspectives;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use NormanHuth\NovaBasePackage\HasIcons;

abstract class Perspective
{
    use HasIcons;

    /**
     * Custom perspective slug.
     *
     * @var string|null
     */
    public ?string $perspectiveSlug = null;

    /**
     * The perspective priority to order.
     *
     * @var int
     */
    public int $priority = 999;

    /**
     * The main menu for this Nova perspective.
     *
     * @var Closure|null
     */
    protected ?Closure $mainMenuCallback = null;

    /**
     * The user menu for this Nova perspective.
     *
     * @var Closure|null
     */
    protected ?Closure $userMenuCallback = null;

    /**
     * The unfiltered menu over main menu for this Nova perspective.
     *
     * @var Closure|null
     */
    protected ?Closure $unfilteredMainMenuOverCallback = null;

    /**
     * The unfiltered menu under main menu for this Nova perspective.
     *
     * @var Closure|null
     */
    protected ?Closure $unfilteredMainMenuUnderCallback = null;

    /**
     * The perspective label.
     *
     * @param Request $request
     * @return string
     */
    public abstract function label(Request $request): string;

    /**
     * Override Nova menus.
     *
     * @param Request $request
     * @return void
     */
    public abstract function novaMenus(Request $request): void;

    /**
     * Determine if the filter or action should be available for the given request.
     *
     * @param Request $request
     * @return bool
     */
    public abstract function authorizedToSee(Request $request): bool;

    public function __construct()
    {
        $this->icons['classes']['icon'] = '';
    }

    /**
     * Set the main menu for this Nova perspective.
     *
     * @param Closure(\Illuminate\Http\Request, \Laravel\Nova\Menu\Menu):(\Laravel\Nova\Menu\Menu|array) $menuCallback
     * @return $this
     */
    public function mainMenu(Closure $menuCallback): static
    {
        $this->mainMenuCallback = $menuCallback;

        return $this;
    }

    /**
     * Set the unfiltered menu over main menu for this Nova perspective.
     *
     * @param Closure(\Illuminate\Http\Request, \Laravel\Nova\Menu\Menu):(\Laravel\Nova\Menu\Menu|array) $menuCallback
     * @return $this
     */
    public function unfilteredMainMenuOver(Closure $menuCallback): static
    {
        $this->unfilteredMainMenuOverCallback = $menuCallback;

        return $this;
    }

    /**
     * Set the unfiltered menu under main menu for this Nova perspective.
     *
     * @param Closure(\Illuminate\Http\Request, \Laravel\Nova\Menu\Menu):(\Laravel\Nova\Menu\Menu|array) $menuCallback
     * @return $this
     */
    public function unfilteredMainMenuUnder(Closure $menuCallback): static
    {
        $this->unfilteredMainMenuUnderCallback = $menuCallback;

        return $this;
    }

    /**
     * Set the user menu for this Nova perspective.
     *
     * @param Closure(\Illuminate\Http\Request, \Laravel\Nova\Menu\Menu):(\Laravel\Nova\Menu\Menu|array) $userMenuCallback
     * @return $this
     */
    public function userMenu(Closure $userMenuCallback): static
    {
        $this->userMenuCallback = $userMenuCallback;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @param Request $request
     * @return array|null
     */
    public function serialize(Request $request): ?array
    {
        $this->novaMenus($request);

        return [
            'slug' => $this->getSlug(),
            'label' => $this->label($request),
            'mainMenu' => $this->mainMenuCallback,
            'userMenu' => $this->userMenuCallback,
            'unfilteredMainMenuOver' => $this->unfilteredMainMenuOverCallback,
            'unfilteredMainMenuUnder' => $this->unfilteredMainMenuUnderCallback,
            'authorizedToSee' => $this->authorizedToSee($request),
            'icons' => $this->icons,
            'classes' => $this->classes,
            'priority' => $this->priority,
        ];
    }

    /**
     * Get the slug for this perspective.
     *
     * @return string
     */
    public function getSlug(): string
    {
        if ($this->perspectiveSlug) {
            return $this->perspectiveSlug;
        }

        $class = class_basename(get_called_class());

        if (str_ends_with($class, 'Perspective') && strlen($class) > 11) {
            $class = substr($class, 0, -11);
        }

        return Str::slug(Str::kebab($class));
    }
}
