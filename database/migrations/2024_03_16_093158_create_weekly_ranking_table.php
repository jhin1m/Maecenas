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
        Schema::create('weekly_ranking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comic_id')->nullable();
            $table->unsignedBigInteger('total_views')->default(0);
            $table->foreign('comic_id')->references('id')->on('comics')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_ranking');
    }
};
