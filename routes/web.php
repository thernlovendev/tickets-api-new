<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

// Auth Google 
Route::get('/google-login', 'App\Http\Controllers\GoogleAuthController@redirectToGoogle')->name('google.auth');
Route::get('/auth/google/callback', 'App\Http\Controllers\GoogleAuthController@handleGoogleCallback')->name('google.callback');

// Auth Naver 

Route::get('/naver-login', 'App\Http\Controllers\NaverAuthController@redirectToNaver')->name('naver.auth');
Route::get('/auth/naver/callback', 'App\Http\Controllers\NaverAuthController@handleNaverCallback')->name('naver.callback');

Route::get('reset-password/{token}', 'App\Http\Controllers\RestorePasswordController@showResetPasswordForm')->name('reset.password.get');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/stripe', function () {
    return view('stripe');
})->name('stripe');
