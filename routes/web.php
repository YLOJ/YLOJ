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

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'HomeController@test');

Route::name('problem.')->prefix('problem')->group(function() {
    Route::get('/', 'ProblemsetController@index')->name('index');
    Route::get('/{id}', 'ProblemsetController@show')->name('show')->where('id', '[0-9]+');
    
    Route::any('/add', 'ProblemsetController@add')->name('add');
    Route::post('/add_submit', 'ProblemsetController@add_submit');

    Route::any('/edit/{id}', 'ProblemsetController@edit')->name('edit')->where('id', '[0-9]+');
    Route::post('/edit_submit/{id}', 'ProblemsetController@edit_submit')->name('edit_submit')->where('id', '[0-9]+');
    
    Route::any('/data/{id}', 'ProblemsetController@data')->name('data')->where('id', '[0-9]+');
    Route::post('/data_submit/{id}', 'ProblemsetController@data_submit')->name('data_submit')->where('id', '[0-9]+');
    Route::any('/data_download/{id}', 'ProblemsetController@data_download')->name('data_download')->where('id', '[0-9]+');

    Route::get('/submit/{id}', 'SubmissionController@submitpage')->name('submitpage')->where('id', '[0-9]+');
    Route::post('/submit/{id}', 'SubmissionController@submitcode')->name('submitcode')->where('id', '[0-9]+');

    Route::get('/customtests', 'SubmissionController@customtests')->name('costumtests');
    Route::get('/statistics/{id}', 'SubmissionController@statistics')->name('statistics')->where('id', '[0-9+]');
});

Route::get('/submission', 'SubmissionController@index')->name('submission');
Route::get('/submission/{id}', 'SubmissionController@show')->where('id', '[0-9]+');
