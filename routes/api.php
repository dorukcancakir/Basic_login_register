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

Route::post('/login', 'UserController@login');
Route::post('/register','UserController@register');

Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {
    Route::get('/', 'UserController@user');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'book'],function (){
    Route::post('/add', 'BookController@store');
});


