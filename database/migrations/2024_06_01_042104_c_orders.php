<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->unsignedBigInteger('vendor_id');
            $table->string('payment_identifier')->default('null')->nullable();
            $table->foreign('vendor_id')->references('id')
                                        ->on('vendors');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('total_amount');
            $table->json('order_details');
            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')
                                                ->on('payment_methods');
            $table->string('coupon_code')->default(null)->nullable();
            $table->enum('payment_status', [
                'Paid',
                'Unpaid',
                'Refunded',
                'Cancelled',
                'User canceled',
                'Completed',
                'Failed'
            ])->default('Unpaid');
            $table->enum('order_status', [
                'Pending',
                'Processing',
                'Shipped',
                'Delivered',
                'Cancelled',
                'Returned',
                'Refunded',
                'Returned and Refunded',
                'Failed'
            ])->default('Pending');
            $table->string('tid')->default(null)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
