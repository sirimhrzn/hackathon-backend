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
        Schema::create('product_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->primary();
            $table->foreign('product_id')->references('id')
                                         ->on('products');
            // ->cascadeOnDelete();
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')
                                        ->on('vendors');
            // ->cascadeOnDelete();
            $table->json('details'); // additonal charge details
            $table->json('metadata'); // such as descriptions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
