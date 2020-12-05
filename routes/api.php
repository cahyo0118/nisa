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

    });

    /*Project Generate Options & Global Variables*/

    Route::post(
        'global-variable-values/store',
        'api\Project\VariableValueController@store'
    );

    Route::put(
        'global-variable-values/{global_variable_id}/update',
        'api\Project\VariableValueController@update'
    );

    Route::delete(
        'global-variable-values/{global_variable_id}/delete',
        'api\Project\VariableValueController@delete'
    );


    Route::group(['prefix' => 'generate-options'], function () {

        Route::get('', 'api\GenerateOption\GenerateOptionController@getAll');

    });

});
