<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_description_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->enum('point_type', ['feature', 'specification', 'benefit', 'usage', 'package', 'warning'])->default('feature');
            $table->text('point_text');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('product_id', 'idx_product');
            $table->index('point_type', 'idx_point_type');
            $table->index('sort_order', 'idx_sort_order');

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_description_points');
    }
};
