<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
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

Route::get('/forget', function () {
        return view('forget');
    });

Route::post('/login', 'Auth\AuthController@login');

Route::get('/register', function () {
        return view('register');
    });

// Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::get('/r/{referral}', 'Auth\RegisterController@showRegistrationForm');