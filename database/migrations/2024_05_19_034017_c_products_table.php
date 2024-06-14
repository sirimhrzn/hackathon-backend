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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')
                                        ->on('vendors')
                                        ->cascadeOnDelete();
            $table->string("name");
            $table->enum("enabled", ['y','n']);
            $table->unsignedBigInteger("category_id")->nullable()
                                                     ->default(null);
            $table->foreign('category_id')->references('id')
                                          ->on('categories')
                                          ->cascadeOnDelete();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('added_by');
            $table->foreign('added_by')->references('id')
                                          ->on('users')
                                          ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
