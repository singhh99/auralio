<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');

Auth::routes();
Route::resource('/change-password','ChangePasswordController');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/AllCountries', 'CountryController@index')->name('AllCountries');
Route::post('/AddCountry','CountryController@store')->name('AddContury');


Route::resource('/Country','CountryController');
Route::resource('/State','StateController');
Route::resource('/City','CityController');
Route::resource('/City/{id}/destroy','CityController@destroy');
Route::resource('/Feature','FeatureController');
Route::resource('/Service','ServiceController');
Route::resource('/Type','SaloonTypeController');
Route::resource('/Booking-Status','BookingStatusController');
Route::resource('/Admin-User','AdminUserController');
Route::resource('/Saloon','SaloonController');
Route::resource('/saloon-images','SaloonImageController');

Route::get('/Saloon-Images/{id}/all-images/{web?}','SaloonImageController@all_images');
Route::get('/Sallon-Images/{id}/add-images','SaloonImageController@add_images');
Route::get('/service_list', 'SaloonController@service_list');
Route::delete('/delete-service', 'SaloonController@delete_service');
Route::post('/add-service', 'SaloonController@service_store');
Route::get('/edit-service','SaloonController@edit_service');
Route::post('/Admin-approval','SaloonController@admin_approval');
Route::post('/update-service', 'SaloonController@update_service');


//route to fetch all features  for api
Route::get('/All-Features','FeatureController@all_features');

//route to fetch all  saloon type
Route::get('/All-Saloon-Type','SaloonTypeController@all_saloon_types');

//route to fetch all  booking status
Route::get('/All-Booking-Status','BookingStatusController@all_booking_status');

//route for saloon vendor login

Route::post('/Saloon-Login','SaloonLoginController@saloon_login');

Route::post('/Vendor-Register','SaloonController@vendor_registartion');
Route::post('/Vendor-Update/{id}','SaloonController@vendor_update');