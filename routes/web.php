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
    return view('welcome');
});

Route::get('user/{id?}', function ($id = 1) {
    return view('user.profile', ['id' => $id]);
})->name('user.profile');

Route::get('page/{id}', function ($id) {
    return view('page.show', ['id' => $id]);
})->where('id', '[0-9]+');

Route::get('page/css', function () {
    return view('page.style');
});

Route::get('/task/{id}/delete', function ($id) {
    return '<form method="post" action="' . route('task.delete', [$id]) . '">
                <input type="hidden", name="_method", value="delete">
                <input type="hidden", name="_token", value="' . csrf_token() . '">
                <button type="submit"> Delete </button>
            </form>';
});

Route::delete('/delete/{id}', function ($id) {
    return 'Delete ' . $id . ' Successfully';
}) -> name('task.delete');

