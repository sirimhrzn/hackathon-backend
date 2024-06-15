<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GlobalController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'setting'],function(){
    Route::group(['prefix' => 'public'], function (){
        Route::get('/payment-options',[GlobalController::class,'get_payment_options']);
    });
});

Route::group(['prefix' => 'auth'],function(){
    Route::post('/token/refresh', [AuthenticationController::class,'refreshToken']);
    Route::get('/{provider}/callback', [AuthenticationController::class,'callbackHandler']);
    Route::get('/{provider}/url', [AuthenticationController::class,'getAuthorizationURL']);
});

