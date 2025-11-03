<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diary_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checklist_id')->constrained()->cascadeOnDelete();
            $table->date('date')->index();
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'checklist_id', 'date']);
            $table->index(['checklist_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diary_checklist_items');
    }
};
