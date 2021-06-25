<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;
use App\Models\Blog;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/blog','BlogController@index');
// Route::post('/blog','BlogController@store');
// Route::get('/blog/create','BlogController@create');
// Route::get('/blog/{blog}/edit','BlogController@edit');
// Route::get('/blog/{blog}','BlogController@show');
// Route::put('/blog/{blog}','BlogController@update');
// Route::delete('/blog/{blog}','BlogController@destroy');

Route::resource('blog','BlogController');
Route::resource('tag','TagController');


