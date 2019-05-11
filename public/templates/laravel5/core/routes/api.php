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

//    Permissions
    Route::get('permissions', [
        'middleware' => 'permission:permissions_read',
        'uses' => 'api\UserProfileController@getAllPermissions',
    ]);

    Route::put('roles/{id}/update-permissions', [
        'middleware' => 'permission:roles_update',
        'uses' => 'api\UsersController@updateRolePermissions',
    ]);

//    Roles
    Route::get('roles', [
        'middleware' => 'permission:roles_read',
        'uses' => 'api\UserProfileController@getAllRoles',
    ]);

    Route::get('roles', [
        'middleware' => 'permission:users_read',
        'uses' => 'api\RolesController@getAll',
    ]);

    Route::get('roles/search/{keyword}', [
        'middleware' => 'permission:users_read',
        'uses' => 'api\RolesController@getAllByKeyword',
    ]);

    Route::get('roles/{id}', [
        'middleware' => 'permission:users_read',
        'uses' => 'api\RolesController@getOne',
    ]);

    Route::post('roles/store', [
        'middleware' => 'permission:users_create',
        'uses' => 'api\RolesController@store',
    ]);

    Route::put('roles/{id}/update', [
        'middleware' => 'permission:users_update',
        'uses' => 'api\RolesController@update',
    ]);

    Route::delete('roles/{id}/delete', [
        'middleware' => 'permission:users_delete',
        'uses' => 'api\RolesController@destroy',
    ]);

    Route::delete('roles/delete/multiple', [
        'middleware' => 'permission:users_delete',
        'uses' => 'api\RolesController@deleteMultiple',
    ]);
    
//    End Roles

    Route::post('users/{id}/add-role/{role_id}', [
        'middleware' => 'permission:users_update',
        'uses' => 'api\UsersController@addRole',
    ]);

    Route::delete('users/{id}/delete-role/{role_id}', [
        'middleware' => 'permission:users_update',
        'uses' => 'api\UsersController@deleteRole',
    ]);

    // User Profile
    Route::get('me', 'api\UserProfileController@getCurrentUser');

    Route::get('me/auth', 'api\UserProfileController@checkAuth');

    Route::put('me/update', 'api\UserProfileController@updateCurrentUser');

    Route::put('me/update/password', 'api\UserProfileController@updateCurrentUserPassword');

    Route::post('me/update/photo', 'api\UserProfileController@updateCurrentUserPhoto');

    Route::post('me/logout', 'api\UserProfileController@logout');


    // administrator Routes
    Route::get('administrator', [
        'middleware' => 'permission:administrator_read',
        'uses' => 'api\AdministratorController@getAll',
    ]);

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


    // tags Routes
    Route::get('tags', [
        'middleware' => 'permission:tags_read',
        'uses' => 'api\TagsController@getAll',
    ]);

    Route::get('tags/search/{keyword}', [
        'middleware' => 'permission:tags_read',
        'uses' => 'api\TagsController@getAllByKeyword',
    ]);

    Route::get('tags/{id}', [
        'middleware' => 'permission:tags_read',
        'uses' => 'api\TagsController@getOne',
    ]);

    Route::post('tags/store', [
        'middleware' => 'permission:tags_create',
        'uses' => 'api\TagsController@store',
    ]);

    Route::put('tags/{id}/update', [
        'middleware' => 'permission:tags_update',
        'uses' => 'api\TagsController@update',
    ]);

    Route::delete('tags/{id}/delete', [
        'middleware' => 'permission:tags_delete',
        'uses' => 'api\TagsController@destroy',
    ]);

    Route::delete('tags/delete/multiple', [
        'middleware' => 'permission:tags_delete',
        'uses' => 'api\TagsController@deleteMultiple',
    ]);


    // brands Routes
    Route::get('brands', [
        'middleware' => 'permission:brands_read',
        'uses' => 'api\BrandsController@getAll',
    ]);

    Route::get('brands/search/{keyword}', [
        'middleware' => 'permission:brands_read',
        'uses' => 'api\BrandsController@getAllByKeyword',
    ]);

    Route::get('brands/{id}', [
        'middleware' => 'permission:brands_read',
        'uses' => 'api\BrandsController@getOne',
    ]);

    Route::post('brands/store', [
        'middleware' => 'permission:brands_create',
        'uses' => 'api\BrandsController@store',
    ]);

    Route::put('brands/{id}/update', [
        'middleware' => 'permission:brands_update',
        'uses' => 'api\BrandsController@update',
    ]);

    Route::delete('brands/{id}/delete', [
        'middleware' => 'permission:brands_delete',
        'uses' => 'api\BrandsController@destroy',
    ]);

    Route::delete('brands/delete/multiple', [
        'middleware' => 'permission:brands_delete',
        'uses' => 'api\BrandsController@deleteMultiple',
    ]);


    // products Routes
    Route::get('products', [
        'middleware' => 'permission:products_read',
        'uses' => 'api\ProductsController@getAll',
    ]);

    Route::get('products/search/{keyword}', [
        'middleware' => 'permission:products_read',
        'uses' => 'api\ProductsController@getAllByKeyword',
    ]);

    Route::get('products/{id}', [
        'middleware' => 'permission:products_read',
        'uses' => 'api\ProductsController@getOne',
    ]);

    Route::post('products/store', [
        'middleware' => 'permission:products_create',
        'uses' => 'api\ProductsController@store',
    ]);

    Route::put('products/{id}/update', [
        'middleware' => 'permission:products_update',
        'uses' => 'api\ProductsController@update',
    ]);

    Route::delete('products/{id}/delete', [
        'middleware' => 'permission:products_delete',
        'uses' => 'api\ProductsController@destroy',
    ]);

    Route::delete('products/delete/multiple', [
        'middleware' => 'permission:products_delete',
        'uses' => 'api\ProductsController@deleteMultiple',
    ]);



});
