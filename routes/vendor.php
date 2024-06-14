<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'product','middleware' => ['vendor']], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [ProductController::class,'get_products']);
        Route::get('/{product_id}', [ProductController::class,'get_product_by_id']);
        Route::post('/', [ProductController::class,'create_product']);
        Route::patch('/{product_id}', [ProductController::class,'update_product']);
        Route::delete('/{product_id}', [ProductController::class,'delete_product']);
    });
    Route::group(['prefix' => 'public'], function () {
        Route::get('/',[ProductController::class,'get_products']);
        Route::get('/{product_id}', [ProductController::class,'get_product_by_id']);
    });
});

Route::group(['prefix' => 'category','middleware' => ['vendor']],function (){
    Route::group(['prefix' => 'public'],function(){
        Route::get('/',[CategoryController::class,'get_categories']);
    });
    Route::group(['prefix' => 'admin'],function(){
        Route::post('/',[CategoryController::class,'create_category']);
        Route::patch('/',[CategoryController::class,'edit_category']);
    });

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

