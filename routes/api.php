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
Route::get('game', 'Api@index');
Route::get('game/{id}', 'Api@show');
Route::post('game', 'Api@store');
Route::put('game/{game}', 'Api@update');
Route::delete('game/{game}', 'Api@destroy');