<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('authors', 'AuthorController@index');
Route::get('authors/{id}', 'AuthorController@show');
Route::post('authors', 'AuthorController@store');
Route::put('authors/{id}', 'AuthorController@update');
Route::delete('authors/{id}', 'AuthorController@destroy');

Route::get('publishers', 'PublisherController@index');
Route::get('publishers/{id}', 'PublisherController@show');
Route::post('publishers', 'PublisherController@store');
Route::put('publishers/{id}', 'PublisherController@update');
Route::delete('publishers/{id}', 'PublisherController@destroy');

Route::get('books', 'BookController@index');
Route::get('books/{id}', 'BookController@show');
Route::post('books', 'BookController@store');
Route::put('books/{id}', 'BookController@update');
Route::delete('books/{id}', 'BookController@destroy');