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

    Route::group(['prefix' => 'projects'], function () {

        Route::get('', 'api\Project\ProjectController@getAll');

        Route::get('{id}/detail', 'api\Project\ProjectController@getOne');

        Route::post('store', 'api\Project\ProjectController@store');

        Route::put('{id}/update', 'api\Project\ProjectController@update');

        Route::delete('{id}/delete', 'api\Project\ProjectController@delete');

        /*Generate Options & Global Variables*/
        Route::group(['prefix' => '{id}/generate-options'], function () {

            Route::get(
                '{generate_option_id}/global-variables',
                'api\Project\ProjectGenerateOptionController@getGlobalVariables'
            );

        });

        /*Menus*/
        Route::group(['prefix' => '{id}/menus'], function () {

            Route::get(
                '',
                'api\Project\Menu\MenuController@getAllByProjectId'
            );

        });

    });

    /*Project Generate Options & Global Variables*/
    Route::group(['prefix' => 'global-variable-values'], function () {
        Route::post(
            'store',
            'api\Project\VariableValueController@store'
        );

        Route::put(
            '{global_variable_id}/update',
            'api\Project\VariableValueController@update'
        );

        Route::delete(
            '{global_variable_id}/delete',
            'api\Project\VariableValueController@delete'
        );
    });

    /*Menus*/
    Route::group(['prefix' => 'menus'], function () {
        Route::get(
            '{id}/detail',
            'api\Project\Menu\MenuController@getOne'
        );

        Route::get(
            '{id}/sub-menus',
            'api\Project\Menu\MenuController@getAllSubMenu'
        );

        Route::post(
            'store',
            'api\Project\Menu\MenuController@store'
        );

        Route::put(
            '{id}/update',
            'api\Project\Menu\MenuController@update'
        );

        Route::delete(
            '{id}/delete',
            'api\Project\Menu\MenuController@delete'
        );

    });

    /*Menu Data Setting*/
    Route::group(['prefix' => 'menu-data-settings'], function () {

        Route::get(
            '{menu_id}/fields',
            'api\Project\Menu\DataSettingController@getFields'
        );

        Route::put(
            'update-criteria',
            'api\Project\Menu\DataSettingController@updateCriteria'
        );

        Route::put(
            'update-datasets-criteria',
            'api\Project\Menu\DataSettingController@updateDatasetCriteria'
        );

        Route::put(
            'update-load-reference',
            'api\Project\Menu\DataSettingController@updateLoadReference'
        );

    });

    /*Import DB*/
    Route::group(['prefix' => 'import-db'], function () {

        Route::post(
            'upload-temp',
            'api\Project\ImportDB\ImportDBController@uploadTemp'
        );

        Route::get(
            'read-db',
            'api\Project\ImportDB\ImportDBController@readDB'
        );

        Route::post(
            'to-project/{project_id}',
            'api\Project\ImportDB\ImportDBController@importDBToProject'
        );

    });

    Route::group(['prefix' => 'generate-options'], function () {

        Route::get('', 'api\GenerateOption\GenerateOptionController@getAll');

    });

});
