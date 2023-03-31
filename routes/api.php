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

Route::post('login', 'App\Http\Controllers\API\Auth\UserController@authenticate')->name('login');
Route::post('register', 'App\Http\Controllers\API\Auth\UserController@register')->name('register');
Route::post('forgot-password', 'App\Http\Controllers\API\Auth\ForgotPasswordController@forgotPassword')->name('forgot.password'); 
Route::post('reset-password', 'App\Http\Controllers\API\Auth\ForgotPasswordController@submitResetPasswordForm')->name('reset.password'); 

// Route::group(['middleware' => ['jwt.verify']], function() {    
    Route::get('profile','App\Http\Controllers\API\Auth\UserController@getAuthenticatedUser')->name('profile');
    Route::post('profile-update', 'App\Http\Controllers\API\Auth\UserController@updateProfile')->name('profile.update');
    Route::post('logout', 'App\Http\Controllers\API\Auth\UserController@logout')->name('logout');
    Route::post('delete-my-user', 'App\Http\Controllers\API\Auth\UserController@deleteMyUser')->name('destroy.my.user');
	
	Route::prefix('companies')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\CompaniesController@index')->name('company.index')->middleware();
	});

    Route::prefix('categories')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\CategoriesController@index')->name('category.index')->middleware();
		Route::get('/{category}', 'App\Http\Controllers\API\CategoriesController@show')->name('category.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\CategoriesController@store')->name('category.create')->middleware();
		Route::put('/{category}', 'App\Http\Controllers\API\CategoriesController@update')->name('category.update')->middleware();
		Route::delete('/{category}', 'App\Http\Controllers\API\CategoriesController@destroy')->name('category.delete')->middleware();
	});

	Route::prefix('cities')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\CitiesController@index')->name('cities.index')->middleware();
		Route::get('/{company}', 'App\Http\Controllers\API\CitiesController@getCitiesByCompany')->name('cities.create')->middleware();
		Route::post('/', 'App\Http\Controllers\API\CitiesController@store')->name('cities.create')->middleware();
	});

	Route::prefix('images')->group(function() {
		Route::post('/', 'App\Http\Controllers\API\ImagesController@store')->name('images.create')->middleware();
	});

	Route::prefix('tickets')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\TicketsController@index')->name('tickets.index')->middleware();
		Route::get('/{ticket}', 'App\Http\Controllers\API\TicketsController@show')->name('tickets.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\TicketsController@store')->name('tickets.create')->middleware();
		Route::put('/{ticket}', 'App\Http\Controllers\API\TicketsController@update')->name('tickets.update')->middleware();
	});

	Route::prefix('reservations')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\ReservationsController@index')->name('reservation.index')->middleware();
		Route::get('/{reservation}', 'App\Http\Controllers\API\ReservationsController@show')->name('reservation.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\ReservationsController@store')->name('reservations.create')->middleware();
		Route::put('/{reservation}', 'App\Http\Controllers\API\ReservationsController@update')->name('reservations.update')->middleware();
		// Route::post('/{reservation}/payments', 'App\Http\Controllers\API\ReservationsController@payment')->name('reservation.payment')->middleware();
	});

	Route::prefix('price-lists')->group(function() {
		Route::post('/', 'App\Http\Controllers\API\PriceListsController@store')->name('price.lists.create')->middleware();
	});

// });

Route::post('reservations/{reservation}/payments', 'App\Http\Controllers\API\ReservationsController@payment')->name('reservation.payment')->middleware();