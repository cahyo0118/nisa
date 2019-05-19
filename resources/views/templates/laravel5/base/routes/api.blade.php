{!! $php_prefix !!}

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

@foreach($project->menus()->whereNotNull('table_id')->get() as $menu)

    // {{ $menu->name }} Routes
    Route::get('{!! kebab_case(str_plural($menu->name)) !!}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@getAll',
    ]);

@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/datasets/{!! kebab_case(str_plural($field->relation->table->name)) !!}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@get{!! ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet',
    ]);
@endif
@endif
@endforeach

    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/search/{keyword}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@getAllByKeyword',
    ]);

    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/{id}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@getOne',
    ]);

    Route::post('{!! kebab_case(str_plural($menu->name)) !!}/store', [
        'middleware' => 'permission:{{ $menu->name }}_create',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@store',
    ]);

    Route::put('{!! kebab_case(str_plural($menu->name)) !!}/{id}/update', [
        'middleware' => 'permission:{{ $menu->name }}_update',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@update',
    ]);

    Route::delete('{!! kebab_case(str_plural($menu->name)) !!}/{id}/delete', [
        'middleware' => 'permission:{{ $menu->name }}_delete',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@destroy',
    ]);

    Route::delete('{!! kebab_case(str_plural($menu->name)) !!}/delete/multiple', [
        'middleware' => 'permission:{{ $menu->name }}_delete',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@deleteMultiple',
    ]);

@endforeach

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

});
