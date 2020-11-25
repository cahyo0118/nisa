<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Landing*/
Route::post('register', 'api\Auth\AuthController@register');

Route::post('request-reset-password-code', 'api\Auth\AuthController@requestResetPasswordCode');

Route::post('reset-password', 'api\Auth\AuthController@resetPassword');

/*Auth*/
Route::group(['middleware' => ['auth:api']], function () {

    /*Account*/
    Route::group(['prefix' => 'me'], function () {

        Route::get('', 'api\Account\MeController@getCurrentUser');

        Route::put('update', 'api\Account\MeController@update');

        Route::put('update-password', 'api\Account\MeController@updatePassword');

    });

});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
