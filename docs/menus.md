# Change Menus For A Perspective

All changes are optional.

## Nova Main Menu

You can change the Nova main menu for each perspective with the `mainMenu` method:

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
     /**
     * Override Nova menus.
     *
     * @param Request $request
     * @return void
     */
    public function novaMenus(Request $request): void
    {
        $this->mainMenu(function (Request $request) {
            Nova::mainMenu(function (Request $request) {
                return [
                    PerspektiveSelect::make('asd'),
                    MenuItem::resource(Post::class),
                ];
            });
        });
    }
}
```

## Nova User Menu

You can change the Nova main menu for each perspective with the `userMenu` method:

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
     /**
     * Override Nova menus.
     *
     * @param Request $request
     * @return void
     */
    public function novaMenus(Request $request): void
    {
        $this->userMenu(function (Request $request) {
            Nova::userMenu(function (Request $request, Menu $menu) {
                $menu->prepend(
                    MenuItem::make(
                        'My Profile',
                        "/resources/users/{$request->user()->getKey()}"
                    )
                );
                $menu->prepend(
                    PerspectiveDisclosure::make(),
                );

                return $menu;
            });
        });
    }
}
```

## Custom Menus Of „[Nova Menu Advanced](https://github.com/Muetze42/nova-menu)“ Version 1.7+

### Additional Menu Over The Filter

You can change the additional Nova main menu over the filter for each perspective with the `unfilteredMainMenuOver` method:

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
     /**
     * Override Nova menus.
     *
     * @param Request $request
     * @return void
     */
    public function novaMenus(Request $request): void
    {
        $this->unfilteredMainMenuOver(function (Request $request) {
            return [
                PerspektiveSelect::make('asd'),
                MenuItem::resource(Car::class),
            ];
        });
    }
}
```

### Additional Menu Under The Filter

You can change the additional Nova main menu under the filter for each perspective with the `unfilteredMainMenuUnder` method:

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
     /**
     * Override Nova menus.
     *
     * @param Request $request
     * @return void
     */
    public function novaMenus(Request $request): void
    {
        $this->unfilteredMainMenuUnder(function (Request $request) {
            return [
                PerspektiveSelect::make('asd'),
                MenuItem::resource(Car::class),
            ];
        });
    }
}
```
