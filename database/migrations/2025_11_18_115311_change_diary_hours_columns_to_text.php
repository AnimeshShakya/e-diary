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
        Schema::table('diaries', function (Blueprint $table) {
            $table->text('sleep')->change();
            $table->text('management')->change();
            $table->text('utilized')->change();
            $table->text('wasted')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diaries', function (Blueprint $table) {
            $table->integer('sleep')->change();
            $table->integer('management')->change();
            $table->integer('utilized')->change();
            $table->integer('wasted')->change();
        });
    }
};
