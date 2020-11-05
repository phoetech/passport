<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
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

Route::group(['middleware' => ['cors', 'json.response']], function () {
	Route::post('/login', 'Auth\AuthController@login')->name('login.api');
    Route::post('/register','Auth\AuthController@register')->name('register.api');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'Auth\AuthController@logout')->name('logout.api');
});