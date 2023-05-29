# Perspective Selects

Each perspective select is only displayed if at least 2 perspectives exist!

## PerspektiveSelect

```php
use NormanHuth\NovaPerspectives\Menu\PerspektiveSelect; // [tl! focus]

Nova::mainMenu(function (Request $request) {
    return [
        PerspektiveSelect::make(), // [tl! focus]
    ];
});
```

## PerspectiveDisclosure

To display this element in the user menu must be [Nova Menu Advanced](https://github.com/Muetze42/nova-menu) installed in version 1.7 or newer!

```php
use NormanHuth\NovaPerspectives\Menu\PerspectiveDisclosure; // [tl! focus]

Nova::userMenu(function (Request $request, Menu $menu) {
    $menu->prepend(
        PerspectiveDisclosure::make(), // [tl! focus]
    );

    return $menu;
});
```

### Icons

#### Font Awesome

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
    /**
     * The icons for JSON serialization.
     *
     * @var array
     */
    protected array $icons = [
        'fontawesome' => 'fa-brands fa-laravel',
        'heroicon' => null,
        'image' => null,
        'html' => null,
        'height' => 18,
    ];
}
```

#### Heroicon

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
    /**
     * The icons for JSON serialization.
     *
     * @var array
     */
    protected array $icons = [
        'fontawesome' => null,
        'heroicon' => 'chart-bar',
        'image' => null,
        'html' => null,
        'height' => 18,
    ];
}
```

#### SVG

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
    /**
     * The icons for JSON serialization.
     *
     * @var array
     */
    protected array $icons = [
        'fontawesome' => null,
        'heroicon' => null,
        'image' => null,
        'html' => '<svg ...>',
        'height' => 18,
    ];
}
```

### Optional: Install Font Awesome

If you don't have Font Awesome integrated in Nova, but want to use it, you can optionally install Font Awesome Free with this command.

```nothing
php artisan nova-package:font-awesome
```

