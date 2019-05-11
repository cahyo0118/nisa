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


    // posts Routes
    Route::get('posts', [
        'middleware' => 'permission:posts_read',
        'uses' => 'api\PostsController@getAll',
    ]);

    Route::get('posts/search/{keyword}', [
        'middleware' => 'permission:posts_read',
        'uses' => 'api\PostsController@getAllByKeyword',
    ]);

    Route::get('posts/{id}', [
        'middleware' => 'permission:posts_read',
        'uses' => 'api\PostsController@getOne',
    ]);

    Route::post('posts/store', [
        'middleware' => 'permission:posts_create',
        'uses' => 'api\PostsController@store',
    ]);

    Route::put('posts/{id}/update', [
        'middleware' => 'permission:posts_update',
        'uses' => 'api\PostsController@update',
    ]);

    Route::delete('posts/{id}/delete', [
        'middleware' => 'permission:posts_delete',
        'uses' => 'api\PostsController@destroy',
    ]);

    Route::delete('posts/delete/multiple', [
        'middleware' => 'permission:posts_delete',
        'uses' => 'api\PostsController@deleteMultiple',
    ]);


    // sentence two Routes
    Route::get('sentence two', [
        'middleware' => 'permission:sentence two_read',
        'uses' => 'api\Sentence twoController@getAll',
    ]);

    Route::get('sentence two/search/{keyword}', [
        'middleware' => 'permission:sentence two_read',
        'uses' => 'api\Sentence twoController@getAllByKeyword',
    ]);

    Route::get('sentence two/{id}', [
        'middleware' => 'permission:sentence two_read',
        'uses' => 'api\Sentence twoController@getOne',
    ]);

    Route::post('sentence two/store', [
        'middleware' => 'permission:sentence two_create',
        'uses' => 'api\Sentence twoController@store',
    ]);

    Route::put('sentence two/{id}/update', [
        'middleware' => 'permission:sentence two_update',
        'uses' => 'api\Sentence twoController@update',
    ]);

    Route::delete('sentence two/{id}/delete', [
        'middleware' => 'permission:sentence two_delete',
        'uses' => 'api\Sentence twoController@destroy',
    ]);

    Route::delete('sentence two/delete/multiple', [
        'middleware' => 'permission:sentence two_delete',
        'uses' => 'api\Sentence twoController@deleteMultiple',
    ]);



});
