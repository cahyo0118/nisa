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

Route::get('tables/{id}/relations/many', 'TableController@relationsMany')->name('tables.relations.many');

Route::put('tables/{id}/fields/sync', 'TableController@syncFields')->name('tables.fields.sync');

//templates
Route::get('fields/{id}/relation/{code}', 'TableController@fieldRelationTemplate');

Route::post('tables/add-new-field', 'TableController@addNewField');

Route::post('tables/add-new-many-to-many-relation', 'TableController@addNewManyToManyRelation');

Route::post('tables/add-new-has-many-relation', 'TableController@addNewHasManyRelation');

//fields
Route::delete('fields/{id}', 'TableController@destroyField')->name('fields.destroy');

//relations
Route::post('fields/{id}/add-new-relation/{code}', 'TableController@addNewRelation');

Route::delete('fields/{id}/relation', 'TableController@deleteFieldRelation');

Route::get('relation/{code}/tables/{table_id}/fields', 'TableController@getAllFieldsSelectForm');

Route::get('relation/{code}/tables/{table_id}/fields/{field_id}', 'TableController@getAllFieldsSelectFormByFieldId');

Route::get('relation/{code}/tables/{table_id}/displays', 'TableController@getAllDisplaysSelectForm');

Route::get('relation/{code}/tables/{table_id}/displays/{field_id}', 'TableController@getAllDisplaysSelectFormByFieldId');

// relations many
Route::get('relation/many/{code}/tables/{table_id}/fields', 'TableController@getAllManyFieldsSelectForm');

Route::get('relation/many/{code}/tables/{table_id}/fields/{field_id}', 'TableController@getAllManyFieldsSelectFormByFieldId');

Route::get('relation/many/{code}/tables/{table_id}/displays', 'TableController@getAllManyDisplaysSelectForm');

Route::get('relation/many/{code}/tables/{table_id}/displays/{field_id}', 'TableController@getAllManyDisplaysSelectFormByFieldId');

Route::delete('relation/many/{id}', 'TableController@deleteManyRelation');

