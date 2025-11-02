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
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('yesterday_sleep_time');
            $table->time('today_wake_time');
            $table->text('text');
            $table->text('urgent_important');
            $table->text('urgent_notimportant');
            $table->text('noturgent_important');
            $table->text('noturgent_notimportant');
            $table->text('good_memo');
            $table->integer('sleep');
            $table->integer('management');
            $table->integer('utilized');
            $table->integer('wasted');
            $table->text('checklist_array');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diaries');
    }
};
