<?php

use Illuminate\Support\Facades\Route;
use Api\V1\ItemController as ItemControllerV1;
use Api\V1\UserController as UserControllerV1;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('sign-in', 'AuthController@signIn');
Route::post('sign-up', 'AuthController@signUp');
Route::post('password/email', 'AuthController@forgot')->name('password.email');
Route::post('password/reset', 'AuthController@reset')->name('password.reset');
Route::get('email/verify/{id}', 'AuthController@verify');

Route::middleware(['auth:api'])->prefix('v1')->group(
    function () {
        Route::apiResources(
            [
                'users' => UserControllerV1::class,
                'items' => ItemControllerV1::class
            ]
        );
    }
);
