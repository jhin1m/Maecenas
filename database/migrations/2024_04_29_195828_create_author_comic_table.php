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
        Schema::create('author_comic', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_author')->unsigned();
            $table->bigInteger('id_comic')->unsigned()->nullable();
            $table->foreign('id_author')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('id_comic')->references('id')->on('comics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_comic');
    }
};
