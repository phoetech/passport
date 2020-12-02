<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
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
    Route::post('/register','AuthController@register')->name('register.api');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'AuthController@logout')->name('logout.api');
});

Route::prefix('auth')->namespace('API')->group(function () {
    Route::get('/phone/check', 'AuthController@checkPhone');
    Route::post('/code/send', 'AuthController@sendSMSCode');
    Route::get('/code/check', 'AuthController@checkSMSCode');
    Route::get('/trueId/check', 'AuthController@checkTrueId');
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::put('/password', 'AuthController@changePassword');
});
