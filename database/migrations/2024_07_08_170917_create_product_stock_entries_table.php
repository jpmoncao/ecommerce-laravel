<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_stock_entries', function (Blueprint $table) {
            $table->string('id_product_stock_entry', 36)->primary();
            $table->string('product_variation_id');
            $table->float('quantity');
            $table->string('observation')->nullable();
            $table->foreign('product_variation_id')->references('id_product_variation')->on('product_variations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_entries');
    }
};
