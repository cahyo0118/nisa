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
        'middleware' => 'permission:{{ str_plural($menu->name) }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@getAll',
    ]);

@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/datasets/{!! !empty($field->relation->relation_name) ? kebab_case(str_plural($field->relation->relation_name)) : kebab_case(str_plural($field->relation->table->name)) !!}', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@get{!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_plural($field->relation->relation_name))) : ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet',
    ]);

@php
$field_reference = null;
$reference = DB::table('menu_load_references')->where('menu_id', $menu->id)->where('field_id', $field->id)->first();
if (!empty($reference)) {
    $field_reference = \App\Field::find($reference->field_reference_id);
}
@endphp
@if(!empty($field_reference))

    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/datasets/{!! !empty($field->relation->relation_name) ? kebab_case(str_plural($field->relation->relation_name)) : kebab_case(str_plural($field->relation->table->name)) !!}/relation/{!! snake_case($field_reference->name) !!}/{{!! snake_case($field_reference->name) !!}}', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@get{!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_plural($field->relation->relation_name))) : ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSetBy{!! ucfirst(camel_case($field_reference->name)) !!}',
    ]);
@endif
@endif
@endif
@endforeach

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")

    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/{id}/relations/{!! !empty($relation->relation_name) ? kebab_case(str_plural($relation->relation_name)) : kebab_case(str_plural($relation->table->name)) !!}', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Relation',
    ]);

    Route::post('{!! kebab_case(str_plural($menu->name)) !!}/{id}/relations/{!! !empty($relation->relation_name) ? kebab_case(str_plural($relation->relation_name)) : kebab_case(str_plural($relation->table->name)) !!}/store', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_update',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@add{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}',
    ]);

    Route::delete('{!! kebab_case(str_plural($menu->name)) !!}/{id}/relations/{!! !empty($relation->relation_name) ? kebab_case(str_plural($relation->relation_name)) : kebab_case(str_plural($relation->table->name)) !!}/{item_id}/remove', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_update',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@remove{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}',
    ]);
@endif
@endforeach
@endif

    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/search/{keyword}', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@getAllByKeyword',
    ]);

    Route::get('{!! kebab_case(str_plural($menu->name)) !!}/{id}', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@getOne',
    ]);

    Route::post('{!! kebab_case(str_plural($menu->name)) !!}/store', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_create',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@store',
    ]);

    Route::put('{!! kebab_case(str_plural($menu->name)) !!}/{id}/update', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_update',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@update',
    ]);

    Route::delete('{!! kebab_case(str_plural($menu->name)) !!}/{id}/delete', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_delete',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@destroy',
    ]);

    Route::delete('{!! kebab_case(str_plural($menu->name)) !!}/delete/multiple', [
        'middleware' => 'permission:{{ str_plural($menu->name) }}_delete',
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

@foreach($project->tables()->where('name', 'users')->first()->fields as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    Route::get('users/datasets/{!! kebab_case(str_plural($field->relation->table->name)) !!}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst(camel_case($menu->name)) }}Controller@get{!! ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet',
    ]);
@endif
@endif
@endforeach

});
