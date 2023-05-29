# Nova Perspectives

With this package it is possible to create different perspectives in Nova. The following _optional_ features are available for each perspective:

* Different main menu
* Different user menu
* Different fields, cards, filters etc. (`$request->viaPerspective` is global available in Nova)
* Different styling via CSS child classes

[![Live Preview](https://raw.githubusercontent.com/Muetze42/Muetze42/main/files/btn-live-preview.jpg)](https://nova-demo.huth.it)


## Installation

You can install the package via composer:

```nothing
composer require norman-huth/nova-perspectives
```

## Middleware

Add the `NormanHuth\NovaPerspectives\Http\Middleware\HandleNovaPerspektive` to `/config/nova.php`. 

```php

use NormanHuth\NovaPerspectives\Http\Middleware\HandleNovaPerspektive; // [tl! focus]

return [
    //...
    'middleware' => [
        'web',
        HandleInertiaRequests::class,
        HandleNovaPerspektive::class, // [tl! focus]
        DispatchServingNovaEvent::class,
        BootTools::class,
    ],
    //...
];
```
