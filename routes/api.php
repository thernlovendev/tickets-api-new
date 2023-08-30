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


Route::group(['middleware' => ['jwt.verify']], function() {    
	Route::get('refresh-token','App\Http\Controllers\API\Auth\UserController@refresh')->name('refresh.token');

	Route::get('email/verify/{id}/{hash}', 'App\Http\Controllers\API\Auth\ApiVerificationController@verify')->name('verification.verify');
	Route::post('email/resend', 'App\Http\Controllers\API\Auth\ApiVerificationController@resend')->name('verification.resend');
    Route::get('profile','App\Http\Controllers\API\Auth\UserController@getAuthenticatedUser')->name('profile');
    Route::post('profile-update', 'App\Http\Controllers\API\Auth\UserController@updateProfile')->name('profile.update');
    Route::post('logout', 'App\Http\Controllers\API\Auth\UserController@logout')->name('logout');
    Route::post('delete-my-user', 'App\Http\Controllers\API\Auth\UserController@deleteMyUser')->name('destroy.my.user');
	
	Route::prefix('companies')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\CompaniesController@index')->name('company.index')->middleware();
	});

    Route::prefix('categories')->group(function() {
		Route::get('/subcategories', 'App\Http\Controllers\API\CategoriesController@getSubcategories')->name('subcategory.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\CategoriesController@store')->name('category.create')->middleware();
		Route::put('/{category}', 'App\Http\Controllers\API\CategoriesController@update')->name('category.update')->middleware();
		Route::delete('/{category}', 'App\Http\Controllers\API\CategoriesController@destroy')->name('category.delete')->middleware();
	});

	Route::prefix('cities')->group(function() {
		Route::get('/{company}', 'App\Http\Controllers\API\CitiesController@getCitiesByCompany')->name('cities.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\CitiesController@store')->name('cities.create')->middleware();
		Route::put('/{city}', 'App\Http\Controllers\API\CitiesController@changeStatus')->name('cities.change.status')->middleware();
		Route::put('/{city}/edit', 'App\Http\Controllers\API\CitiesController@update')->name('cities.update')->middleware();
		Route::delete('/{city}', 'App\Http\Controllers\API\CitiesController@delete')->name('cities.delete')->middleware();
	});

	Route::prefix('images')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\ImagesController@index')->name('images.index')->middleware();
		Route::post('/', 'App\Http\Controllers\API\ImagesController@store')->name('images.create')->middleware();
	});

	Route::prefix('tickets')->group(function() {
		Route::post('/', 'App\Http\Controllers\API\TicketsController@store')->name('tickets.create')->middleware();
		Route::put('/ordering', 'App\Http\Controllers\API\TicketsOrderingController@updateOrdering')->name('ticket.ordering.update')->middleware();
		Route::put('/{ticket}', 'App\Http\Controllers\API\TicketsController@update')->name('tickets.update')->middleware();
		Route::delete('/{ticket}', 'App\Http\Controllers\API\TicketsController@delete')->name('tickets.delete')->middleware();
		Route::get('/{ticket}/price', 'App\Http\Controllers\API\TicketsController@getSinglePrice')->name('ticket.single.price')->middleware();
		Route::put('/{ticket}/ticket-schedules/{ticketSchedule}', 'App\Http\Controllers\API\TicketSchedulesController@update')->name('ticket.shcedule.update')->middleware();
		Route::delete('/{ticket}/ticket-schedules/{ticketSchedule}', 'App\Http\Controllers\API\TicketSchedulesController@delete')->name('ticket.shcedule.delete')->middleware();
	});

	Route::prefix('reservations')->group(function() {
		Route::post('/', 'App\Http\Controllers\API\ReservationsController@store')->name('reservations.create')->middleware();
		Route::put('/{reservation}', 'App\Http\Controllers\API\ReservationsController@update')->name('reservations.update')->middleware();
		// Route::post('/{reservation}/payments', 'App\Http\Controllers\API\ReservationsController@payment')->name('reservation.payment')->middleware();
		// Route::post('/{reservation}/reservation-subitems/{reservationSubItem}', 'App\Http\Controllers\API\UsersDashboard@downloadTicket')->name('reservations.ticket.download')->middleware();
		Route::post('/{reservation}/reservation-subitems/{reservationSubItem}/email', 'App\Http\Controllers\API\UsersDashboard@emailDownloadTicket')->name('reservations.ticket.email')->middleware();
		Route::delete('/{reservation}', 'App\Http\Controllers\API\ReservationsController@delete')->name('reservation.delete')->middleware();
		
	});

	Route::prefix('price-lists')->group(function() {
		Route::post('/', 'App\Http\Controllers\API\PriceListsController@store')->name('price.lists.create')->middleware();
		Route::put('/{price_list}', 'App\Http\Controllers\API\PriceListsController@update')->name('price.lists.update')->middleware();
		Route::delete('/{price_list}', 'App\Http\Controllers\API\PriceListsController@delete')->name('price.lists.delete')->middleware();
	});

	Route::prefix('schedule')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\ScheduleOverviewController@index')->name('schedule.index')->middleware();
	});

	Route::prefix('inventories')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\InventoriesController@index')->name('inventory.index')->middleware();
		Route::get('/{ticket_id}/{type}', 'App\Http\Controllers\API\InventoriesController@details')->name('inventory.detail')->middleware();
		Route::get('/download-pdf-zip', 'App\Http\Controllers\API\InventoriesController@downloadPdfZip')->name('inventory.detail')->middleware();
		Route::get('/stock-balance', 'App\Http\Controllers\API\InventoriesController@stockBalance')->name('inventory.stock.balance')->middleware();
		Route::put('/{stock}/change-status', 'App\Http\Controllers\API\InventoriesController@changeStatus')->name('inventory.change.status')->middleware();
		Route::post('/correction-balance', 'App\Http\Controllers\API\InventoriesController@stockCorrection')->name('inventory.correction.balance')->middleware();
		Route::post('/bulk-upload', 'App\Http\Controllers\API\InventoriesController@bulkUpload')->name('inventory.bulk.upload')->middleware();
		Route::post('/bulk-upload-zip', 'App\Http\Controllers\API\InventoriesController@bulkUploadZip')->name('inventory.bulk.upload.zip')->middleware();
		Route::post('/reservation/{reservation}/reservation-subitems/{reservationSubItem}', 'App\Http\Controllers\API\InventoriesController@downloadTickets')->name('inventory.download.reservation')->middleware();
	
	});

	Route::get('/roles', 'App\Http\Controllers\API\RolesController@index')->name('roles.index')->middleware();
	Route::prefix('users')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\Auth\UserController@index')->name('users.index')->middleware();
		Route::get('/{user}', 'App\Http\Controllers\API\Auth\UserController@show')->name('users.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\Auth\UserController@create')->name('users.create')->middleware();
		Route::put('/{user}', 'App\Http\Controllers\API\Auth\UserController@edit')->name('users.edit')->middleware();
		Route::put('/{user}/change-status', 'App\Http\Controllers\API\Auth\UserController@changeStatus')->name('users.change.status')->middleware();
		Route::delete('/{user}', 'App\Http\Controllers\API\Auth\UserController@deleteUser')->name('users.delete')->middleware();
	});

	Route::prefix('templates')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\TemplatesController@index')->name('templates.index')->middleware();
		Route::get('/{template}', 'App\Http\Controllers\API\TemplatesController@show')->name('templates.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\TemplatesController@store')->name('templates.create')->middleware();
		Route::post('/images', 'App\Http\Controllers\API\TemplatesController@createTemplateImage')->name('templates.image.create')->middleware();
		Route::put('/{template}', 'App\Http\Controllers\API\TemplatesController@update')->name('templates.update')->middleware();
		Route::delete('/{template}', 'App\Http\Controllers\API\TemplatesController@delete')->name('templates.delete')->middleware();
	});

	Route::prefix('header-gallery')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\HeaderGalleryController@index')->name('header_gallery.index')->middleware();
		Route::get('/{header_gallery}', 'App\Http\Controllers\API\HeaderGalleryController@show')->name('header_gallery.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\HeaderGalleryController@store')->name('header_gallery.create')->middleware();
		Route::put('/{header_gallery}', 'App\Http\Controllers\API\HeaderGalleryController@update')->name('header_gallery.update')->middleware();
		Route::delete('/{header_gallery}', 'App\Http\Controllers\API\HeaderGalleryController@delete')->name('header_gallery.delete')->middleware();
	});

	Route::prefix('navigation-menu')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\NavigationMenuController@index')->name('navigation.index')->middleware();
		Route::get('/{navigation_menu}', 'App\Http\Controllers\API\NavigationMenuController@show')->name('navigation.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\NavigationMenuController@store')->name('navigation.create')->middleware();
		Route::put('/{navigation_menu}', 'App\Http\Controllers\API\NavigationMenuController@update')->name('navigation.update')->middleware();
		Route::delete('/{navigation_menu}', 'App\Http\Controllers\API\NavigationMenuController@delete')->name('navigation.delete')->middleware();
	});

	Route::prefix('navigation-submenu')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\NavigationSubMenuController@index')->name('navigation_submenu.index')->middleware();
		Route::get('/{navigation_submenu}', 'App\Http\Controllers\API\NavigationSubMenuController@show')->name('navigation_submenu.show')->middleware();
		Route::post('/', 'App\Http\Controllers\API\NavigationSubMenuController@store')->name('navigation_submenu.create')->middleware();
		Route::put('/', 'App\Http\Controllers\API\NavigationSubMenuController@update')->name('navigation_submenu.update')->middleware();
		Route::delete('/{navigation_menu}', 'App\Http\Controllers\API\NavigationSubMenuController@delete')->name('navigation_submenu.delete')->middleware();
	});

	Route::prefix('booking')->group(function() {
		Route::get('/', 'App\Http\Controllers\API\BookingController@index')->name('booking.index')->middleware();
	});

	Route::prefix('configurations')->group(function() {
		Route::get('/payment-type', 'App\Http\Controllers\API\ConfigurationController@getPaymentConfiguration')->name('configuration.payment.get')->middleware();
		Route::put('/payment-type', 'App\Http\Controllers\API\ConfigurationController@updatePaymentConfiguration')->name('configuration.payment.update')->middleware();
	});

});

//Tamice users
Route::put('booking/{sub_item}/date-change', 'App\Http\Controllers\API\BookingController@dateChange')->name('booking.date.change')->middleware();
Route::put('booking/{cartId}', 'App\Http\Controllers\API\BookingController@update')->name('booking.update')->middleware();
Route::get('categories', 'App\Http\Controllers\API\CategoriesController@index')->name('category.index')->middleware();
Route::get('categories/{category}', 'App\Http\Controllers\API\CategoriesController@show')->name('category.show')->middleware();
Route::prefix('categories')->group(function() {
	Route::get('/subcategories', 'App\Http\Controllers\API\CategoriesController@getSubcategories')->name('subcategory.show')->middleware();
});
Route::get('cities', 'App\Http\Controllers\API\CitiesController@index')->name('cities.index')->middleware();
Route::get('order-lookup', 'App\Http\Controllers\API\BookingController@orderLookup')->name('order.lookup')->middleware();
Route::get('price-lists', 'App\Http\Controllers\API\PriceListsController@getByCategory')->name('price.lists.get.by.category')->middleware();
Route::get('price-lists-selected', 'App\Http\Controllers\API\PriceListsController@index')->name('price.lists.selected')->middleware();
Route::prefix('price-lists')->group(function() {
	Route::get('/{price_list}', 'App\Http\Controllers\API\PriceListsController@show')->name('price.lists.show')->middleware();
});
Route::get('product-seats', 'App\Http\Controllers\API\SeatsController@index')->name('seats.index')->middleware();
Route::get('product', 'App\Http\Controllers\API\PriceListsController@getBySubcategory')->name('price.get.by.subcategory')->middleware();
Route::get('reservations', 'App\Http\Controllers\API\ReservationsController@index')->name('reservation.index')->middleware();	
Route::prefix('reservations')->group(function() {
	Route::get('/{reservation}', 'App\Http\Controllers\API\ReservationsController@show')->name('reservation.show')->middleware();
});
Route::post('reservations/user-create', 'App\Http\Controllers\API\ReservationsController@createByUser')->name('reservations.create.by.user')->middleware();
Route::put('reservations/user-create/{reservation}', 'App\Http\Controllers\API\ReservationsController@updateByUser')->name('reservations.update.by.user')->middleware();
Route::get('reservation-sub-item/options-schedules', 'App\Http\Controllers\API\ReservationsController@filterScheduleOptions')->name('schedule.options.index')->middleware();
Route::post('reservation-sub-item/options-schedules', 'App\Http\Controllers\API\ReservationsController@filterScheduleOptionsPost')->name('schedule.options.index.post')->middleware();
Route::get('reservation-sub-item/{reservation_sub_item}/options-schedules', 'App\Http\Controllers\API\ReservationsController@getScheduleOptions')->name('schedule.options')->middleware();
Route::post('reservation-sub-item/{reservation_sub_item}/options-schedules', 'App\Http\Controllers\API\ReservationsController@createScheduleOptions')->name('schedule.options.create')->middleware();
Route::post('reservations/create-card', 'App\Http\Controllers\API\ReservationsController@saveCard')->name('reservation.saveCard')->middleware();
Route::post('reservations/{reservation}/payments', 'App\Http\Controllers\API\ReservationsController@payment')->name('reservation.payment')->middleware();
Route::get('tickets', 'App\Http\Controllers\API\TicketsController@index')->name('tickets.index')->middleware();
Route::get('tickets/{ticket}', 'App\Http\Controllers\API\TicketsController@show')->name('tickets.show')->middleware();
Route::get('tickets/{ticket}/sold', 'App\Http\Controllers\API\TicketsController@getSold')->name('ticket.sold')->middleware();

Route::get('/test-pdf', function(){
	$file = '/home/flopez/Documentos/Repositorios/tickets-api-new/storage/app/public/stock_pdfs/20230818201518/C245878.pdf';
	return Response::download($file);
});
