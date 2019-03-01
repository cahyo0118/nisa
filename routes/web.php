<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('landing');


Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/me', 'ProfileController@index')->name('profile');

    Route::put('/me/update', 'ProfileController@update')->name('profile.update');

    Route::put('/me/password/update', 'ProfileController@updateCurrentUserPassword')->name('profile.password.update');

    Route::resource('samples', 'SampleController');

    Route::get('samples/all/search', 'SampleController@search')->name('samples.search');

});

Route::resource('projects', 'ProjectController');

Route::get('projects/all/search', 'ProjectController@search')->name('projects.search');

Route::resource('tables', 'TableController');

Route::get('tables/all/search', 'TableController@search')->name('tables.search');

Route::get('tables/{id}/fields', 'TableController@fields')->name('tables.fields');

Route::put('tables/{id}/fields/sync', 'TableController@syncFields')->name('tables.fields.sync');

Route::post('tables/add-new-field', 'TableController@addNewField')->name('tables.addNewField');

//fields
Route::delete('fields/{id}', 'TableController@destroyField')->name('fields.destroy');