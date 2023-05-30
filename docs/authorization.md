# Authorization

One can authorize who can see and use the perspective using the `authorizedToSee` method:

```php
use NormanHuth\NovaPerspectives\Perspective;

class MyPerspective extends Perspective
{
    /**
     * Determine if the filter or action should be available for the given request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->is_admin;
    }
}
```
