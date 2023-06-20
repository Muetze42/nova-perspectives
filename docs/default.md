# Default Perspective

If a user has not selected a perspective, the first perspective is activated.   
This can be affect with perspective `$priority`.

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
    /**
     * The perspective priority to order.
     *
     * @var int
     */
    public int $priority = 42;
}
```

### Selected Default Perspective For Each User

You can add the method `defaultPerspective` to Your `Authenticatable` Model.  
_The following examples assume that a string column names `default_perspective` in the users tables._

```php
class User extends Authenticatable
{
    public function defaultPerspective(): string
    {
        return $this->default_perspective;
    }
}
```

You can use the `PerspectiveHelper` class at creating a select field element.

```php
use NormanHuth\NovaPerspectives\PerspectiveHelper; // [tl! focus]
use Laravel\Nova\Fields\Select;

class User extends Resource
{
    public function fields(NovaRequest $request): array
    {
        return [
            //..
            Select::make(__('Default Perspective'), 'default_perspective')
                ->options(PerspectiveHelper::options()) // [tl! focus]
                ->displayUsingLabels(),
            //..
        ];
    }
}
```
