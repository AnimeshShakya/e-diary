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
        Schema::table('affirmations', function (Blueprint $table) {
            // Drop the old type and description columns
            $table->dropColumn(['type', 'description']);

            // Add new columns for morning and night affirmations
            $table->text('morning_affirmation')->nullable();
            $table->text('night_affirmation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affirmations', function (Blueprint $table) {
            // Restore old columns
            $table->tinyInteger('type');
            $table->text('description');

            // Drop new columns
            $table->dropColumn(['morning_affirmation', 'night_affirmation']);
        });
    }
};
