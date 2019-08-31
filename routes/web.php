<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great!  |
*/

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::name('problem.')->prefix('problem')->group(function() {
    Route::get('/', 'ProblemsetController@index')->name('index');
    Route::get('/{id}', 'ProblemsetController@show')->name('show')->where('id', '[0-9]+');
    Route::get('/{id}/{file}', 'ProblemsetController@view_file')->name('view_file')->where('id', '[0-9]+')->where('file','\S+');
    
    Route::any('/add', 'ProblemsetController@add')->name('add');
    Route::post('/add_submit', 'ProblemsetController@add_submit');

    Route::any('/edit/manager/{id}', 'ProblemsetController@manager')->name('manager')->where('id', '[0-9]+');
    Route::post('/update_manager/{id}', 'ProblemsetController@update_manager')->name('update_manager')->where('id', '[0-9]+');
    Route::any('/edit/{id}', 'ProblemsetController@edit')->name('edit')->where('id', '[0-9]+');
    Route::post('/edit_submit/{id}', 'ProblemsetController@edit_submit')->name('edit_submit')->where('id', '[0-9]+');

    Route::any('/upload/{id}', 'ProblemsetController@upload')->name('upload')->where('id', '[0-9]+');
    Route::post('/upload_file/{id}', 'ProblemsetController@upload_file')->name('upload_file')->where('id', '[0-9]+');
    Route::any('/delete_file/{id}/{file}', 'ProblemsetController@delete_file')->name('delete_file')->where('id', '[0-9]+')->where('file','\S+');;
    
    Route::any('/data/{id}', 'ProblemsetController@data')->name('data')->where('id', '[0-9]+');
    Route::post('/data_submit/{id}', 'ProblemsetController@data_submit')->name('data_submit')->where('id', '[0-9]+');
    Route::post('/save_config/{id}', 'ProblemsetController@save_config')->name('save_config')->where('id', '[0-9]+');
    Route::post('/data_format/{id}', 'ProblemsetController@data_format')->name('data_format')->where('id', '[0-9]+');
    Route::post('/format_check/{id}', 'ProblemsetController@format_check')->name('format_check')->where('id', '[0-9]+');
    Route::any('/data_download/{id}', 'ProblemsetController@data_download')->name('data_download')->where('id', '[0-9]+');

    Route::get('/submit/{id}', 'SubmissionController@submitpage')->name('submitpage')->where('id', '[0-9]+');
    Route::post('/submit/{id}', 'SubmissionController@submitcode')->name('submitcode')->where('id', '[0-9]+');

    Route::get('/customtests', 'SubmissionController@customtests')->name('customtests');
    Route::get('/statistics/{id}', 'SubmissionController@statistics')->name('statistics')->where('id', '[0-9]+');
});

Route::get('/submission', 'SubmissionController@index')->name('submission');
Route::get('/submission/{id}', 'SubmissionController@show')->where('id', '[0-9]+');

Route::get('/submission/rejudge/{id}', 'SubmissionController@rejudge')->where('id', '[0-9]+');
Route::get('/submission/rejudge_problem/{id}', 'SubmissionController@rejudge_problem')->where('id', '[0-9]+');
Route::get('/submission/rejudge_problem_ac/{id}', 'SubmissionController@rejudge_problem_ac')->where('id', '[0-9]+');

Route::get('/submission/delete/{id}', 'SubmissionController@delete_submission')->where('id', '[0-9]+');
Route::get('/submission/delete_problem/{id}', 'SubmissionController@delete_problem_submission')->where('id', '[0-9]+');

Route::name('contest.')->prefix('contest')->group(function() {
    Route::get('/', 'ContestController@index')->name('index');
    Route::get('/{id}', 'ContestController@show')->name('show')->where('id', '[0-9]+');

    Route::any('/add', 'ContestController@add')->name('add');
    Route::post('/add_submit', 'ContestController@add_submit');
   
	Route::any('/edit/{id}', 'ContestController@edit')->name('edit')->where('id', '[0-9]+');
    Route::post('/edit_submit/{id}', 'ContestController@edit_submit')->where('id', '[0-9]+');

	Route::get('/standings/{id}', 'ContestController@standings')->name('standings')->where('id', '[0-9]+');
	Route::get('/submission/{id}', 'ContestController@submission')->name('submission')->where('id', '[0-9]+');
	Route::get('/mysubmission/{id}', 'ContestController@mysubmission')->name('submission')->where('id', '[0-9]+');

    Route::get('/{cid}/problem/{pid}', 'ContestController@showproblem')->where('cid', '[0-9]+')->where('pid', '[0-9]+');
    Route::get('/{cid}/submit/{pid}', 'ContestController@submitpage')->where('cid', '[0-9]+')->where('pid', '[0-9]+');
    Route::post('/{cid}/submit/{pid}', 'ContestController@submitcode')->where('cid', '[0-9]+')->where('pid', '[0-9]+');
});
