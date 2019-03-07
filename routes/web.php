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

Route::get('/problem/{id}', 'ProblemsetController@showProblem');
Route::post('/problem/add', 'ProblemsetController@add');
Route::post('/problem/add_submit', 'ProblemsetController@add_submit');
Route::post('/problem/edit/{id}', 'ProblemsetController@edit');
