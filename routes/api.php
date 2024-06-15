<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'setting'],function(){
    Route::group(['prefix' => 'public'], function (){
        Route::get('/payment-options',[GlobalController::class,'get_payment_options']);
        Route::get('/locations',[GlobalController::class,'get_available_locations']);
        Route::get('/callback/khalti',[PaymentController::class,'handle_khalti']);
    });
});

Route::group(['prefix' => 'auth'],function(){
    Route::post('/token/refresh', [AuthenticationController::class,'refreshToken']);
    Route::get('/{provider}/callback', [AuthenticationController::class,'callbackHandler']);
    Route::get('/{provider}/url', [AuthenticationController::class,'getAuthorizationURL']);
    Route::post('/signup',[AuthenticationController::class,'sign_up']);
    Route::post('/signin',[AuthenticationController::class,'sign_in']);
});

Route::group(['prefix' => 'order'],function(){
    Route::post('/place-order',[PaymentController::class,'handle_order']);
});
