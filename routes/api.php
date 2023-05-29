<?php

use Illuminate\Support\Facades\Route;
use NormanHuth\NovaPerspectives\Http\Controllers\PerspectiveController;

/*
|--------------------------------------------------------------------------
| Nova Perspectives API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build! <- OK
|
*/

Route::post('switch-perspective', [PerspectiveController::class, 'switch']);
