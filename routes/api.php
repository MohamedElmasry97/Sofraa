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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

    /**
     * auth cycle for client
     */

Route::group(['prefix' => 'v1', 'namespace' => 'APIs'], function () {
    Route::post('register_client', 'client\AuthController@registerClient');
    Route::post('login_client', 'client\AuthController@loginClient');
    Route::post('reset_password_client', 'client\AuthController@resetPasswordClient');
    Route::post('new_password_client', 'client\AuthController@setNewPasswordClient');

    /**
     * auth cycle for resturant
     */

    Route::post('register_resturant', 'resturant\AuthController@registerResturant');
    Route::post('login_resturant', 'resturant\AuthController@loginResturant');
    Route::post('reset_password_resturant', 'resturant\AuthController@resetPasswordResturant');
    Route::post('new_password_resturant', 'resturant\AuthController@setNewPasswordResturant');

    /**
     * general APIs
     */

    Route::get('cities', 'MainController@cities');
    Route::get('neighborhoods', 'MainController@neighborhoods');
    Route::get('list_resturants', 'MainController@listResturants');
    Route::get('list_food', 'resturant\MainController@listResturantFood');
    Route::get('comments_rates', 'MainController@commentsAndRates');
    Route::get('contact', 'MainController@contact');
    Route::get('resturant', 'MainController@showResturant');
    Route::get('offers', 'MainController@offers');
    Route::get('list_notifications', 'MainController@listNotifications');

    /**
     * under auth client below
     */

    Route::group(['middleware' => 'auth:client'], function () {
        Route::post('register_token_client', 'client\AuthController@registerTokenClient');
        Route::post('create_comment', 'client\MainController@createComment');
        Route::post('new_order', 'client\MainController@newOrder');
        Route::get('list_order', 'client\MainController@myOrders');
        Route::get('show_order', 'client\MainController@showOrder');
        Route::get('confirm_order', 'client\MainController@confirmOrder');
        Route::get('decline_order', 'client\MainController@declineOrder');
        Route::post('edit_client', 'client\AuthController@editClient');
        Route::post('remove_token_client', 'client\AuthController@removeToken');
    });

    /**
     * under auth resturant below
     */

    Route::group(['middleware' => 'auth:resturant'], function () {
        Route::post('register_token_resturant', 'resturant\AuthController@registerTokenResturant');
        Route::post('create_offer', 'resturant\MainController@createOffers');
        Route::get('list_notifications', 'resturant\MainController@listNotifications');
        Route::post('new_food', 'resturant\MainController@newFood');
        Route::post('edit_resturant', 'resturant\AuthController@editClient');
        Route::post('delete_food', 'resturant\MainController@deleteFood');
        Route::post('list_orders', 'resturant\MainController@myOrders');
        Route::get('show_order', 'resturant\MainController@showOrder');
        Route::post('accept_order', 'resturant\MainController@acceptOrder');
        Route::post('reject_order', 'resturant\MainController@rejectOrder');
        Route::post('list_offers', 'resturant\MainController@myOffers');
        Route::post('new_offer', 'resturant\MainController@newOffer');
        Route::post('update_offer', 'resturant\MainController@updateOffer');
        Route::post('delete_offer', 'resturant\MainController@deleteOffer');
        Route::get('change_status', 'resturant\MainController@changeState');
        Route::get('commissions', 'resturant\MainController@commissions');
        Route::post('remove_token_resturant', 'resturant\AuthController@removeToken');
    });
});
