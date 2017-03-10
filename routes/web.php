<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/', function () {
    return view('home');
})->middleware('auth', 'menu')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/items', 'ItemController@items')->name('items');
Route::post('/item/search', 'ItemController@search');

Route::resource('dashboard', 'DashboardController');
Route::resource('item', 'ItemController');

Route::resource('/inventory', 'InventoryController');
Route::resource('/cost', 'CostController');

Route::get('/categories', 'CategoryController@categories')->name('categories');
Route::resource('/category', 'CategoryController');

// Settings routes
Route::get('/settings', 'SettingController@index')->name('settings');
Route::post('/upload', 'SettingController@uploadcsv');
Route::post('/persist', 'SettingController@persist');
Route::get('/profile/{id}', 'SettingController@getprofile')->name('profile');

// User routes
Route::get('/user', 'UserController@getUser')->name('users');
Route::post('/user/{id}', 'UserController@postUser');


// Customer Component Routes
Route::get('/customers', 'CustomerController@getCustomers')->name('customers');
Route::post('/customer', 'CustomerController@postCustomers');
Route::put('/customer/{id}', 'CustomerController@updateCustomer');
Route::delete('/customer/{id}', 'CustomerController@deleteCustomer');

Route::get('/customer/{id}/history', 'CustomerController@getHistory');


// Sales Controller
Route::resource('/sales', 'SalesController');
Route::post('/sales/search', 'SalesController@search');


// Suspend Sales
Route::post('/sale/suspend', 'SuspendedSalesController@suspendSale');



// Supplier Routes
Route::resource('/suppliers', 'SupplierController');
