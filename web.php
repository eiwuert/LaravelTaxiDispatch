<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/



Route::group(['middleware' => ['roles:Admin|Employee|HR-Manager']], function() {
       Route::get('/home', 'HomeController@index');
});


Route::get('/', function () {
    return view('welcome');
})->middleware('roles:Admin|Employee|HR-Manager');

Auth::routes();

//Route::get('/login', 'Auth\LoginController@index');
//Route::get('/home', 'HomeController@index');
Route::get('profile', function() {
    // Only authenticated users may enter...
})->middleware('auth');