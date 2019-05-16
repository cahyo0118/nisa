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
/*Global Variable*/
Route::post('ajax/generate-options/{id}/variables/{variable_id}', 'GlobalVariableController@ajaxStore');

Route::delete('ajax/variables/{variable_id}/delete', 'GlobalVariableController@ajaxDelete');

Route::put('ajax/projects/{id}/variables/{variable_id}', 'GlobalVariableController@ajaxFillVariable');

//Route::post('ajax/projects/{id}/variables', 'GlobalVariableController@ajaxStore');

/*Generate Options*/
Route::get('generate-options', 'GenerateOptionController@index')->name('generate_options.index');

Route::post('ajax/generate-options/store', 'GenerateOptionController@ajaxStore');

Route::delete('ajax/generate-options/{id}/delete', 'GenerateOptionController@ajaxDelete');

/* Project */
Route::resource('projects', 'ProjectController');

Route::get('projects/all/search', 'ProjectController@search')->name('projects.search');

Route::delete('ajax/projects/{id}/delete', 'ProjectController@ajaxDeleteProject');

//Menus
Route::get('projects/{id}/menus/interface', 'ProjectController@menus')->name('projects.menus');

//Tables
Route::get('projects/{id}/tables/interface', 'ProjectController@tables')->name('projects.tables');
/* END Project */

/* Menus and Sub Menus */
Route::resource('menus', 'MenuController');
//Route::resource('projects/{project_id}/menus', 'MenuController');

Route::get('menus/{id}/subs', 'MenuController@subMenus')->name('menus.subs');

Route::get('menus/all/search', 'MenuController@search')->name('menus.search');

Route::post('ajax/projects/{id}/menus/{parent_menu_id}', 'MenuController@ajaxStore');

Route::put('ajax/projects/{id}/menus/{parent_menu_id}', 'MenuController@ajaxUpdate');

Route::put('ajax/menus/{menu_id}/datasets/{table_id}/update', 'MenuController@ajaxUpdateDataset');

Route::delete('ajax/menus/{parent_menu_id}/delete', 'MenuController@ajaxDeleteMenu');

Route::get('projects/{id}/menus', 'MenuController@getAllMenuByProjectId');

Route::get('projects/{id}/menus/{parent_menu_id}/sub-menus', 'MenuController@getAllSubMenuByMenuId');
/* END Menus and Sub Menus */

Route::resource('projects/{project_id}/tables', 'TableController');

Route::get('tables/all/search', 'TableController@search')->name('tables.search');

Route::get('tables/{id}/fields', 'TableController@fields')->name('tables.fields');

Route::get('tables/{id}/relations/many', 'TableController@relationsMany')->name('tables.relations.many');

Route::put('tables/{id}/fields/sync', 'TableController@syncFields')->name('tables.fields.sync');

Route::delete('ajax/tables/{table_id}/delete', 'TableController@ajaxDeleteTable');

//templates
Route::get('fields/{id}/relation/{code}', 'TableController@fieldRelationTemplate');

Route::post('tables/add-new-field', 'TableController@addNewField');

Route::post('projects/{project_id}/tables/add-new-many-to-many-relation', 'TableController@addNewManyToManyRelation');

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

Route::get('relation/many/{code}/tables/{table_id}/fields/local', 'TableController@getAllManyFieldsSelectFormLocal');

Route::get('relation/many/{code}/tables/{table_id}/fields/{field_id}', 'TableController@getAllManyFieldsSelectFormByFieldId');

Route::get('relation/many/{code}/tables/{table_id}/fields/{field_id}/local', 'TableController@getAllManyFieldsSelectFormByFieldIdLocal');

Route::get('relation/many/{code}/tables/{table_id}/displays', 'TableController@getAllManyDisplaysSelectForm');

Route::get('relation/many/{code}/tables/{table_id}/displays/{field_id}', 'TableController@getAllManyDisplaysSelectFormByFieldId');

Route::delete('relation/many/{id}', 'TableController@deleteManyRelation');

//Generate
Route::post('ajax/projects/{id}/{template}/generate', 'ProjectController@ajaxGenerateProject');

