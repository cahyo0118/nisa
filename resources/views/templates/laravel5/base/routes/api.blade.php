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

    // User Profile
    Route::get('me', 'api\UserProfileController@getCurrentUser');

    Route::get('me/auth', 'api\UserProfileController@checkAuth');

    Route::put('me/update', 'api\UserProfileController@updateCurrentUser');

    Route::put('me/update/password', 'api\UserProfileController@updateCurrentUserPassword');

    Route::post('me/update/photo', 'api\UserProfileController@updateCurrentUserPhoto');

    Route::post('me/logout', 'api\UserProfileController@logout');

@foreach($project->menus()->whereNotNull('table_id')->get() as $menu)

    // {{ $menu->name }} Routes
    Route::get('{{ $menu->name }}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst($menu->name) }}Controller@getAll',
    ]);

    Route::get('{{ $menu->name }}/search/{keyword}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst($menu->name) }}Controller@getAllByKeyword',
    ]);

    Route::get('{{ $menu->name }}/{id}', [
        'middleware' => 'permission:{{ $menu->name }}_read',
        'uses' => 'api\{{ ucfirst($menu->name) }}Controller@getOne',
    ]);

    Route::post('{{ $menu->name }}/store', [
        'middleware' => 'permission:{{ $menu->name }}_create',
        'uses' => 'api\{{ ucfirst($menu->name) }}Controller@store',
    ]);

    Route::put('{{ $menu->name }}/{id}/update', [
        'middleware' => 'permission:{{ $menu->name }}_update',
        'uses' => 'api\{{ ucfirst($menu->name) }}Controller@update',
    ]);

    Route::delete('{{ $menu->name }}/{id}/delete', [
        'middleware' => 'permission:{{ $menu->name }}_delete',
        'uses' => 'api\{{ ucfirst($menu->name) }}Controller@destroy',
    ]);

    Route::delete('{{ $menu->name }}/delete/multiple', [
        'middleware' => 'permission:{{ $menu->name }}_delete',
        'uses' => 'api\{{ ucfirst($menu->name) }}Controller@deleteMultiple',
    ]);

@endforeach


});
