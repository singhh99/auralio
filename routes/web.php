<?php

use App\Http\Component\FCMPush;
use Illuminate\Http\Request;
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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();
Route::resource('/forgot-password','Auth\ResetPasswordController');
Route::post('/verify-otp','Auth\ResetPasswordController@otp_verify');
Route::post('/update-admin-password','Auth\ResetPasswordController@update_password');
Route::resource('/change-password','ChangePasswordController');
//Route::get('/home', 'HomeController@index')->name('home');
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
Route::resource('/Cancel-Reason','CancelReasonController');
Route::post('/Update-User-Status','AdminUserController@update_user_status');
Route::resource('/Role','RoleController');
Route::resource('/Permission','PermissionController');
Route::resource('/RolePermission','RoleHasPermissionController');
Route::resource('/Saloon','SaloonController');
Route::resource('/saloon-images','SaloonImageController');

Route::get('/Saloon-Images/{id}/all-images/{web?}','SaloonImageController@all_images');
Route::get('/Sallon-Images/{id}/add-images','SaloonImageController@add_images');
Route::post('/Saloon-Images/{id}/{web?}','SaloonImageController@add_images_web');  //added
Route::delete('/saloon-image/{saloon_image}','SaloonImageController@delete_images');  //added
Route::get('/service_list/{id}/{web?}', 'SaloonController@service_list');

Route::delete('/delete-service', 'SaloonController@delete_service');
Route::post('/add-service', 'SaloonController@service_store');
Route::get('/edit-service','SaloonController@edit_service');
Route::post('/Admin-approval','SaloonController@admin_approval');
Route::post('/update-service', 'SaloonController@update_service');
Route::post('/update-commission_rate/{id}', 'SaloonController@update_saloon_commission_rate');

//route to fetch all user cancel reason
Route::get('/Customer-Cancel-Reason','CancelReasonController@customer_cancel_reason');

//route to fetch all user cancel reason
Route::get('/Salon-Cancel-Reason','CancelReasonController@salon_cancel_reason');

//route to fetch all features  for api
Route::get('/All-Features','FeatureController@all_features');

//route to fetch all saloon type
Route::get('/All-Saloon-Type','SaloonTypeController@all_saloon_type');

//route to fetch all  booking status
Route::get('/All-Booking-Status','BookingStatusController@all_booking_status');

//route to frtch all saloon working  days
Route::get('/Working-Days','SaloonController@saloon_working_days');

//route to fetch all saloon services
Route::get('/Saloon-Services','ServiceController@all_salloon_services');
//route for saloon vendor login

Route::post('/Saloon-Login','SaloonLoginController@saloon_login');
// Route::post('/Saloon-Login','SaloonLoginController@saloon_login');
Route::post('/OTP-Verification','SaloonLoginController@verify_otp');

Route::post('/Vendor-Register/{id}','SaloonController@vendor_registartion');
Route::post('/Vendor-Update/{id}','SaloonController@vendor_update');

//rpute for role and permission
Route::get('/permisiondenied','ErrorController@access_denied');
Route::get('/access','ErrorController@access');

//route for customers

Route::resource('/Customer','CustomerController');
Route::post('/Customer-OTP-Verification','CustomerController@customer_otp_verification');
Route::post('/Customer-Update/{id}','CustomerController@update_customer_details');
Route::post('/Salon-Filter','CustomerController@salon_filter');
Route::get('/Salon-details/{id}','CustomerController@salon_deatils');
Route::get('/salon-slots/{id}/{date}','CustomerController@salon_slots');
Route::post('/customer-token-updation','CustomerController@customer_token_updation');
Route::post('paymentResponse','CustomerBookingController@paymentResponse');

Route::resource('/CustomerBooking','CustomerBookingController');
Route::post('/CustomerBookingByCash','CustomerBookingController@customerBookingByCash');
Route::post('/CustomerBookingRefund','CustomerBookingController@CustomerBookingRefund');
Route::post('/UpComing-Booking','CustomerBookingController@customer_upcoming_booking');
Route::get('/UpComing-Booking-User/{id}','CustomerBookingController@customer_upcoming_booking_user');
Route::post('/Cancel-Booking','CustomerBookingController@cancel_booking');
Route::post('/Reschedule-Booking','CustomerBookingController@reschedule_booking');
Route::post('/Favourite-salon','CustomerBookingController@customer_favourite_salon');
Route::post('/Unfavourite-salon','CustomerBookingController@unfavourite_salon');
Route::get('/Customer-favourite-salon/{id}','CustomerBookingController@customer_fav_salon');
Route::post('/Customer-rating','CustomerController@customer_rating');
Route::post('/Customer-order-details','CustomerBookingController@customer_order_details');
Route::get('/Customer-payment-history/{id}','CustomerBookingController@customer_payment_history');
Route::post('/my-appointments/','CustomerBookingController@myAppointments');
Route::get('/BookingDeatilByID','CustomerBookingController@booking_details');
// Route::get('/Salon-List','CustomerController@salon_list'); 
// Route::get('/saloonlist','CustomerController@saloon_list');
Route::post('/saloonLists','CustomerController@SaloonLists');
Route::get('/bookingsinfo','CustomerBookingController@index');
Route::get('/rescheduledbookings','CustomerBookingController@rescheduled');
Route::get('/todayrescheduledbookings','CustomerBookingController@today_rescheduled');
Route::get('/cancelledbookings','CustomerBookingController@cancelled');
Route::get('/todaycancelledbookings','CustomerBookingController@today_cancelled');

Route::get('/All-Ordrers/{id}/{web?}','VendorDetailController@all_order_deatils'); 
Route::post('/VendorOrderDetails','VendorDetailController@vendor_order_details');
Route::post('/VendorOrder','VendorDetailController@vendor_order');
Route::post('/vendornotifications','VendorDetailController@notifications_details');
Route::post('/Customernotifications','CustomerBookingController@notifications_details');
Route::post('/vendorbookingdetail','VendorDetailController@Vendor_booking_details');
Route::post('/updatesalonstatus','VendorDetailController@on_off_salon');
Route::get('/showsalonstatus','VendorDetailController@show_on_off');
Route::post('/vendorlocaldata','VendorDetailController@vendor_local_data');

Route::post('/app-details','VendorDetailController@appointmentsDetails');
Route::post('/Update-Order-Status','VendorDetailController@update_order_status');


Route::get('/Lastest-Orders/{id}','CustomerDetailController@customer_latest_order');
Route::get('/Customer-Detail/{id}','CustomerDetailController@customer_detail');
Route::get('/Order-History/{id}','CustomerDetailController@customer_odrer_history');

//route for saloon registration through number
 Route::post('/Salon-Registration','SaloonLoginController@salon_registartion');
 Route::post('/Salon-Verification','SaloonLoginController@saloon_otp_verification');
 //route for aggrement
 Route::resource('/Aggrement','AggrementController');
Route::get('/Vendors-Orders/{saloon_id}/{id}','VendorController@index');

Route::get('/revenue','RevenueController@index');
Route::get('/earningbycash/{id}','RevenueController@earningbycash');
Route::get('/earningviaonline/{id}','RevenueController@earningviaonline');
Route::get('/earningintotal/{id}','RevenueController@earningintotal');
Route::get('/vendorearnings','RevenueController@get_vendor_earnings');

Route::get('/test-fcm', function (Request $request) {
    // $token = 'edCr3wn3PWw:APA91bFssNvsjC_R6xkYvg8IJb-E2s9SeFxOP5btLUDW5hPwmJTRGzhKX8AxvawMtiJxdlQzdFT2WQXsznJIPe86KUcI1FbR7Zt4tondNXKeLhSSL5_doOPzxNsknodvWvMksmxXHX8B';
    $token= $request->get('token')??'eSgugPs7C2g:APA91bFouY_l9Eaua-TJk_JJMaQ1W0me3ztSWIow8qu9HzMKAt5HWgZBwcOs3sxZhTJ4xf33jpzN0G-mrBb-_tIvLhP7gnv2e0PHuD4DPLMqHh2ISs6OB4qGMPPxqHEW3pHgi_G4v1mf
    ';
    $payload = [
        'title' => $request->get('title')??"This is title",
        'description' => $request->get('description')??'This is long text description',
        'type' => $request->get('type')??'payment',
        'user_id' => $request->get('user_id')??45,
        'identifier' => $request->get('identifier')??'order-123',
    ];
    FCMPush::sendPushToSingleDevice($payload, $token, true);
});


Route::get('/updatestatus','CronController@update_booking_status');
