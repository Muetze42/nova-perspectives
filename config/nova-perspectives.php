<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Perspectives Directory
    |--------------------------------------------------------------------------
    |
    | The location of "Laravel Nova Perspectives" classes.
    | All Perspective classes in this directory will be registered.
    | Each Perspective class must extend \NormanHuth\NovaPerspectives\Perspective
    |
    */

    'directory' => app_path('Nova/Perspectives'),

    /*
    |--------------------------------------------------------------------------
    | Perspectives Cookie Name
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the cookie used to identify a selected
    | perspective. The name specified here will get used every time a perspective
    | is selected by a user.
    |
    */

    'cookie' => 'perspective',

    /*
    |--------------------------------------------------------------------------
    | Perspectives Collection
    |--------------------------------------------------------------------------
    |
    | Create another Collection class to generate the Collections perspective
    | differently or from a different source.
    | The static method and return type must remain as in the original class.
    |
    */

    'collection' => \NormanHuth\NovaPerspectives\Support\NovaPerspectivesCollection::class,
];
