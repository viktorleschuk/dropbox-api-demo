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

Route::get('/files', 'FilesController@index');
Route::post('/files', 'FilesController@store');
Route::delete('/files', 'FilesController@delete');
Route::get('/files/preview', 'FilesController@preview');
