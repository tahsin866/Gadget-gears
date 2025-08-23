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
        Schema::table('addresses', function (Blueprint $table) {
            // Add new fields needed for order form
            $table->string('name')->after('user_id');
            $table->string('phone')->after('name');
            $table->string('email')->after('phone');
            $table->string('district')->after('email');
            $table->string('thana')->after('district');
            $table->string('postoffice')->after('thana');
            $table->text('address')->after('postoffice'); // Main address field
            
            // Modify existing fields to be nullable since we're using new structure
            $table->string('address_line1')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('country')->nullable()->change();
            $table->string('postal_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Drop the new fields
            $table->dropColumn([
                'name', 'phone', 'email', 'district', 'thana', 'postoffice', 'address'
            ]);
            
            // Restore original field constraints
            $table->string('address_line1')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();
            $table->string('postal_code')->nullable(false)->change();
        });
    }
};
