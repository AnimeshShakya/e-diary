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
        Schema::create('diary_entries', function (Blueprint $table) {
            $table->id();
            $table->date('timeRange');
            $table->text('timeContent')->nullable();
            $table->text('timeMatrix')->nullable();
            $table->text('priorities')->nullable();
            $table->text('reminder')->nullable();
            $table->text('dailyAffirmations')->nullable();
            $table->text('forTomorrow')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary_entries');
    }
};
