<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin','middleware' => ['vendor']], function () {
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [ProductController::class,'get_products']);
        Route::get('/id/{product_id}', [ProductController::class,'get_product_by_id']);
        Route::post('/', [ProductController::class,'create_product']);
        Route::patch('/{product_id}', [ProductController::class,'update_product']);
        Route::delete('/{product_id}', [ProductController::class,'delete_product']);
    });
    Route::group(['prefix' => 'order'], function () {
    });
    Route::group(['prefix' => 'store'], function () {
    });
    Route::group(['prefix' => 'user'], function () {
    });
    Route::group(['prefix' => 'setting'], function () {
    });
    Route::group(['prefix' => 'analytic'], function () {
    });
});

Route::group(['prefix' => 'public','middleware' => ['vendor']],function(){
    Route::group(['prefix' => 'category'],function (){
        Route::get('/',[CategoryController::class,'get_categories']);
        Route::post('/',[CategoryController::class,'create_category']);
        Route::patch('/',[CategoryController::class,'edit_category']);
    });
});
