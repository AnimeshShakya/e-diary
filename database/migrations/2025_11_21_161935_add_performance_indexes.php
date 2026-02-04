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
        try {
            Schema::table('daily_scores', function (Blueprint $table) {
                $table->index(['user_id', 'date'], 'daily_scores_user_id_date_index');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }

        try {
            Schema::table('diaries', function (Blueprint $table) {
                $table->index(['user_id', 'date'], 'diaries_user_id_date_index');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('daily_scores', function (Blueprint $table) {
                $table->dropIndex('daily_scores_user_id_date_index');
            });
        } catch (\Exception $e) {
            // Index might not exist
        }

        try {
            Schema::table('diaries', function (Blueprint $table) {
                $table->dropIndex('diaries_user_id_date_index');
            });
        } catch (\Exception $e) {
            // Index might not exist
        }
    }
};
