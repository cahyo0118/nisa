<?php

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

Route::group(array('prefix' => 'v1', 'middleware' => ['cors']), function () {

    Route::post('register', 'api\RegisterController@register');

    Route::get('unauthentication', [
        'uses' => 'api\UserProfileController@handleUnauthentication',
        'as' => 'login',
    ]);

});

Route::group(array('prefix' => 'v1', 'middleware' => ['auth:api', 'cors']), function () {

    // User Profile
    Route::get('me', 'api\UserProfileController@getCurrentUser');

    Route::get('me/auth', 'api\UserProfileController@checkAuth');

    Route::put('me/update', 'api\UserProfileController@updateCurrentUser');

    Route::put('me/update/password', 'api\UserProfileController@updateCurrentUserPassword');

    Route::post('me/update/photo', 'api\UserProfileController@updateCurrentUserPhoto');

    Route::post('me/logout', 'api\UserProfileController@logout');


    // reason Routes
    Route::get('reason', [
        'middleware' => 'permission:reason_read',
        'uses' => 'api\ReasonController@getAll',
    ]);

    Route::get('reason/search/{keyword}', [
        'middleware' => 'permission:reason_read',
        'uses' => 'api\ReasonController@getAllByKeyword',
    ]);

    Route::get('reason/{id}', [
        'middleware' => 'permission:reason_read',
        'uses' => 'api\ReasonController@getOne',
    ]);

    Route::post('reason/store', [
        'middleware' => 'permission:reason_create',
        'uses' => 'api\ReasonController@store',
    ]);

    Route::put('reason/{id}/update', [
        'middleware' => 'permission:reason_update',
        'uses' => 'api\ReasonController@update',
    ]);

    Route::delete('reason/{id}/delete', [
        'middleware' => 'permission:reason_delete',
        'uses' => 'api\ReasonController@destroy',
    ]);

    Route::delete('reason/delete/multiple', [
        'middleware' => 'permission:reason_delete',
        'uses' => 'api\ReasonController@deleteMultiple',
    ]);



});
