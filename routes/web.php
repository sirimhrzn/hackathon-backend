<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Services\OAuthService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::post('/token/refresh', [AuthenticationController::class,'refreshToken']);
Route::get('/{provider}/callback', [AuthenticationController::class,'callbackHandler']);
Route::get('/{provider}/url', [AuthenticationController::class,'getAuthorizationURL']);
