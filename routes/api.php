<?php

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

Route::group(['namespace' => 'Api'], function () {

    // Product routes
  /*  Route::post('/products', 'ProductController@store');
    Route::put('/products/{product}', 'ProductController@update');
    Route::get('/products/{id}', 'ProductController@show');
    Route::delete('/products/{id}', 'ProductController@destroy');
    Route::get('/products', 'ProductController@index');*/

    Route::apiResource('/products','ProductController');

    //Category routes
   /* Route::post('/categories', 'CategoryController@store');
    Route::put('/categories/{category}', 'CategoryController@update');
    Route::get('/categories/{id}', 'CategoryController@show');
    Route::delete('/categories/{id}', 'CategoryController@destroy');
    Route::get('/categories', 'CategoryController@index');*/

    Route::apiResource('/categories','CategoryController');

    //Address routes
   /* Route::post('/addresses', 'AddressController@store');
    Route::put('/addresses/{address}', 'AddressController@update');
    Route::get('/addresses/{id}', 'AddressController@show');
    Route::get('/addresses', 'AddressController@index');*/

    Route::apiResource('/addresses','AddressController')->except(['destroy']);

    //User Auth Routes
    Route::post('/signup', 'ApiAuthController@signup');
    Route::post('/signin', 'ApiAuthController@signin');
    Route::get('/signout', 'ApiAuthController@signout')->middleware('auth:api');
    Route::get('/auth', 'ApiAuthController@user')->middleware('auth:api');

    //User crud

    /*Route::get('/users', 'UserController@index');
    Route::get('/users/{id}', 'UserController@show');
    Route::put('/users/{user}', 'UserController@update');
    Route::delete('/users/{id}', 'UserController@destroy');*/

    Route::apiResource('users','UserController')->except(['store']);

    //Payment Routes
    Route::post('/payments', 'PaymentController@store');

    //Order Routes
    Route::post('/orders', "CustomerOrderController@store");
    Route::put('/orders/{order}', "CustomerOrderController@update");
    Route::get('/orders/{id}', "CustomerOrderController@show");
    Route::delete('/orders/{id}', "CustomerOrderController@destroy");
    Route::get('/orders', "CustomerOrderController@index");

    //ShoppingCart Routes
    /*Route::post('/carts', 'ShoppingCartController@store');
    Route::get('/carts/{id}', 'ShoppingCartController@show');
    Route::delete('/carts/{id}', 'ShoppingCartController@destroy');
    Route::get('/carts', 'ShoppingCartController@index');*/

    Route::apiResource('/carts','ShoppingCartController')->except('update');
    Route::post('/carts/add/{cart}', 'ShoppingCartController@addProducts');
    Route::post('/carts/remove/{cart}', 'ShoppingCartController@removeProduct');
    Route::get('/carts/checkout/{cart}', 'ShoppingCartController@checkOut');
});



