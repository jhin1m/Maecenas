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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string('server')->default('Server#1');
            $table->string('name');
            $table->float('chapter_number');
            $table->bigInteger('views')->default(0);
            $table->string('slug');
            $table->string('title')->nullable();
            $table->integer('has_report')->default(0);
            $table->text('report_message')->nullable();
            $table->foreignId('comic_id')->constrained()->onDelete('cascade');
            $table->enum('fee', ['true', 'false'])->default('false');
            $table->integer('price')->default('500');
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
