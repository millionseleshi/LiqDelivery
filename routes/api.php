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
    Route::post('/products', 'ProductController@store');
    Route::put('/products/{product}', 'ProductController@update');
    Route::get('/products/{id}', 'ProductController@show');
    Route::delete('/products/{id}', 'ProductController@destroy');
    Route::get('/products', 'ProductController@index');

    //Category routes
    Route::post('/categories', 'CategoryController@store');
    Route::put('/categories/{category}', 'CategoryController@update');
    Route::get('/categories/{id}', 'CategoryController@show');
    Route::delete('/categories/{id}', 'CategoryController@destroy');
    Route::get('/categories', 'CategoryController@index');

    //Address routes
    Route::post('/addresses', 'AddressController@store');
    Route::put('/addresses/{address}', 'AddressController@update');
    Route::get('/addresses/{id}', 'AddressController@show');
    Route::get('/addresses', 'AddressController@index');

    //User Auth Routes
    Route::post('/signup', 'ApiAuthController@signup');
    Route::post('/signin', 'ApiAuthController@signin');
    Route::get('/signout', 'ApiAuthController@signout')->middleware('auth:api');
    Route::get('/auth', 'ApiAuthController@user')->middleware('auth:api');
    Route::get('/users','UserController@index');
    Route::get('/users/{id}','UserController@show');
    Route::put('/users/{user}','UserController@update');
    Route::delete('/users/{id}','UserController@destroy');

});



