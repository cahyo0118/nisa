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

    Route::get('administrator', [
//        'middleware' => 'permission:administrator_read',
        'uses' => 'api\AdministratorController@getAll',
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


    // administrator Routes
//    Route::get('administrator', [
//        'middleware' => 'permission:administrator_read',
//        'uses' => 'api\AdministratorController@getAll',
//    ]);

    Route::get('administrator/search/{keyword}', [
        'middleware' => 'permission:administrator_read',
        'uses' => 'api\AdministratorController@getAllByKeyword',
    ]);

    Route::get('administrator/{id}', [
        'middleware' => 'permission:administrator_read',
        'uses' => 'api\AdministratorController@getOne',
    ]);

    Route::post('administrator/store', [
        'middleware' => 'permission:administrator_create',
        'uses' => 'api\AdministratorController@store',
    ]);

    Route::put('administrator/{id}/update', [
        'middleware' => 'permission:administrator_update',
        'uses' => 'api\AdministratorController@update',
    ]);

    Route::delete('administrator/{id}/delete', [
        'middleware' => 'permission:administrator_delete',
        'uses' => 'api\AdministratorController@destroy',
    ]);

    Route::delete('administrator/delete/multiple', [
        'middleware' => 'permission:administrator_delete',
        'uses' => 'api\AdministratorController@deleteMultiple',
    ]);


    // users Routes
    Route::get('users', [
        'middleware' => 'permission:users_read',
        'uses' => 'api\UsersController@getAll',
    ]);

    Route::get('users/search/{keyword}', [
        'middleware' => 'permission:users_read',
        'uses' => 'api\UsersController@getAllByKeyword',
    ]);

    Route::get('users/{id}', [
        'middleware' => 'permission:users_read',
        'uses' => 'api\UsersController@getOne',
    ]);

    Route::post('users/store', [
        'middleware' => 'permission:users_create',
        'uses' => 'api\UsersController@store',
    ]);

    Route::put('users/{id}/update', [
        'middleware' => 'permission:users_update',
        'uses' => 'api\UsersController@update',
    ]);

    Route::delete('users/{id}/delete', [
        'middleware' => 'permission:users_delete',
        'uses' => 'api\UsersController@destroy',
    ]);

    Route::delete('users/delete/multiple', [
        'middleware' => 'permission:users_delete',
        'uses' => 'api\UsersController@deleteMultiple',
    ]);


    // projects Routes
    Route::get('projects', [
        'middleware' => 'permission:projects_read',
        'uses' => 'api\ProjectsController@getAll',
    ]);

    Route::get('projects/search/{keyword}', [
        'middleware' => 'permission:projects_read',
        'uses' => 'api\ProjectsController@getAllByKeyword',
    ]);

    Route::get('projects/{id}', [
        'middleware' => 'permission:projects_read',
        'uses' => 'api\ProjectsController@getOne',
    ]);

    Route::post('projects/store', [
        'middleware' => 'permission:projects_create',
        'uses' => 'api\ProjectsController@store',
    ]);

    Route::put('projects/{id}/update', [
        'middleware' => 'permission:projects_update',
        'uses' => 'api\ProjectsController@update',
    ]);

    Route::delete('projects/{id}/delete', [
        'middleware' => 'permission:projects_delete',
        'uses' => 'api\ProjectsController@destroy',
    ]);

    Route::delete('projects/delete/multiple', [
        'middleware' => 'permission:projects_delete',
        'uses' => 'api\ProjectsController@deleteMultiple',
    ]);



});
