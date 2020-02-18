<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->namespace('Auth')->prefix('auth')->group(function() {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

// Implemented user authentication using jwt
// Route::middleware('jwt.auth')->group(function() {
//     Route::apiResource('authors', 'AuthorController');
//     Route::apiResource('publishers', 'PublisherController');
//     Route::apiResource('books', 'BookController');
// });

// Implemented user authentication using jwt and user authorization using Bouncer
Route::middleware(['jwt.auth', 'can:manage-users'])->group(function() {
    // Routes for managing users (not developed in the practical exercise)
});

Route::middleware(['jwt.auth', 'can:manage-books'])->group(function() {
    Route::apiResource('authors', 'AuthorController')->only([
        'store',
        'update',
    ]);

    Route::apiResource('publishers', 'PublisherController')->only([
        'store',
        'update',
    ]);

    Route::apiResource('books', 'BookController')->only([
        'store',
        'update',
    ]);
});

Route::middleware(['jwt.auth', 'can:view-books'])->group(function() {
    Route::apiResource('authors', 'AuthorController')->only([
        'index',
        'show',
    ]);

    Route::apiResource('publishers', 'PublisherController')->only([
        'index',
        'show',
    ]);

    Route::apiResource('books', 'BookController')->only([
        'index',
        'show',
    ]);
});