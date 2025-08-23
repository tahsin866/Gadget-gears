<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_type_icons', function (Blueprint $table) {
            $table->string('type', 50)->primary();
            $table->string('icon', 10);
            $table->string('label', 50);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default data
        DB::table('feature_type_icons')->insert([
            ['type' => 'feature', 'icon' => '✨', 'label' => 'Key Feature', 'description' => 'Main product features', 'is_active' => true],
            ['type' => 'specification', 'icon' => '📋', 'label' => 'Specification', 'description' => 'Technical specifications', 'is_active' => true],
            ['type' => 'benefit', 'icon' => '👍', 'label' => 'Benefit', 'description' => 'Product benefits', 'is_active' => true],
            ['type' => 'usage', 'icon' => '🔧', 'label' => 'Usage', 'description' => 'How to use', 'is_active' => true],
            ['type' => 'package', 'icon' => '📦', 'label' => 'Package Contents', 'description' => 'What is included', 'is_active' => true],
            ['type' => 'warning', 'icon' => '⚠️', 'label' => 'Warning', 'description' => 'Important warnings', 'is_active' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_type_icons');
    }
};
