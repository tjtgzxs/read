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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/article/{page?}/{search?}','HomeController@getContent')->name('article');
Route::get('/catalogue/{c1}/{c2}','BooksController@getCatalogue')->name('getCatalogue');
Route::get('/book/{c1}/{c2}/{c3}','BooksController@getContent')->name('getContent');
