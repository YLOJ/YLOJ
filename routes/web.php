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

Route::get('/problemset', 'ProblemsetController@index')->name('problemset');

Route::get('/problem/{id}', 'ProblemsetController@showProblem')->where('id', '[0-9]+');
Route::match(['post', 'get'], '/problem/add', 'ProblemsetController@add');
Route::any('/problem/add_submit', 'ProblemsetController@add_submit');
Route::any('/problem/edit/{id}', 'ProblemsetController@edit');