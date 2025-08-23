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
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable for guest orders
            $table->foreignId('user_id')->nullable()->change();
            
            // Add payment method field
            $table->string('payment_method')->after('total');
            
            // Add guest order details
            $table->string('guest_name')->nullable()->after('payment_method');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            
            // Add shipping address directly to orders table for guest orders
            $table->text('shipping_address')->nullable()->after('guest_phone');
            $table->string('shipping_district')->nullable()->after('shipping_address');
            $table->string('shipping_thana')->nullable()->after('shipping_district');
            $table->string('shipping_postoffice')->nullable()->after('shipping_thana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the new fields
            $table->dropColumn([
                'payment_method', 'guest_name', 'guest_email', 'guest_phone',
                'shipping_address', 'shipping_district', 'shipping_thana', 'shipping_postoffice'
            ]);
            
            // Restore user_id to not nullable
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
