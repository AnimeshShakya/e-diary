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
        Schema::create('time_usage_trends', function (Blueprint $table) {
            $table->id();
            $table->foreignid('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->integer('sleep_min');
            $table->integer('management_min');
            $table->integer('utilized_min');
            $table->integer('wasted_min');
            $table->timestamps();

            $table->unique(['user_id', 'date']);
            $table->index(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_usage_trends');
    }
};
