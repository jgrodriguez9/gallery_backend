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

//administracion
Route::prefix('v1')->group(function(){
    Route::prefix('user/auth')->group(function() {
        Route::post('login', 'UserController@login');
        Route::post('/refresh-token', 'UserController@refresh');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->middleware('auth:api')->group(function(){
    //category
    Route::prefix('category')->group(function() {
        Route::get('all', 'CategoriaController@all');
        Route::get('get/{id}', 'CategoriaController@getById');
        Route::post('save', 'CategoriaController@save');
        Route::post('delete', 'CategoriaController@delete');
    });

    //tematica
    Route::prefix('tematica')->group(function() {
        Route::get('all', 'TematicaController@all');
        Route::get('get/{id}', 'TematicaController@getById');
        Route::post('save', 'TematicaController@save');
        Route::post('delete', 'TematicaController@delete');
    });

    //soporte
    Route::prefix('soporte')->group(function() {
        Route::get('all', 'SoporteController@all');
        Route::get('get/{id}', 'SoporteController@getById');
        Route::post('save', 'SoporteController@save');
        Route::post('delete', 'SoporteController@delete');
    });

    //tecnica
    Route::prefix('tecnica')->group(function() {
        Route::get('all', 'TecnicaController@all');
        Route::get('get/{id}', 'TecnicaController@getById');
        Route::post('save', 'TecnicaController@save');
        Route::post('delete', 'TecnicaController@delete');
    });

    //obra
    Route::prefix('obrra')->group(function() {
        Route::get('all', 'ObraController@all');
        Route::get('get/{id}', 'ObraController@getById');
        Route::post('save', 'ObraController@save');
        Route::post('delete', 'ObraController@delete');
    });
});