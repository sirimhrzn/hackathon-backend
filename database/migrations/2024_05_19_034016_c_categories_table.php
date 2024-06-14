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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')
                                        ->on('vendors')
                                        ->cascadeOnDelete();
            $table->string("name");
            $table->enum('active', ['y','n'])->default('y');
            $table->unsignedBigInteger("parent")->nullable()->default(null);
            $table->foreign('parent')->references('id')
                                        ->on('categories')
                                        ->cascadeOnDelete();
            $table->json("tags")->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
