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
Route::get('/test', 'HomeController@test')->name('test');

Route::get('/problemset', 'ProblemsetController@index')->name('problemset');
Route::get('/problem/{id}', 'ProblemsetController@showProblem')->where('id', '[0-9]+');
Route::get('/problem/submit/{id}', 'SubmissionController@submitproblem')->where('id', '[0-9]+');
Route::get('/problem/customtests', 'SubmissionController@customtests');
Route::get('/problem/statistics/{id}', 'SubmissionController@statistics')->where('id', '[0-9]+');

Route::any('/problem/add', 'ProblemsetController@add')->name('add');
Route::post('/problem/add_submit', 'ProblemsetController@add_submit');

Route::any('/problem/edit/{id}', 'ProblemsetController@edit')->name('edit')->where('id', '[0-9]+');
Route::post('/problem/edit_submit/{id}', 'ProblemsetController@edit_submit')->where('id', '[0-9]+');
Route::any('/problem/edit/data/{id}', 'ProblemsetController@edit_data')->where('id','[0-9]+');
Route::post('/problem/edit/data_submit/{id}', 'ProblemsetController@edit_data_submit')->where('id', '[0-9]+');


Route::get('/submission', 'SubmissionController@index')->name('submission');
Route::get('/submission/{id}', 'SubmissionController@show')->where('id', '[0-9]+');
