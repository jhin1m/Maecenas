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
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('origin_name')->nullable();
            $table->string('slug')->unique();
            $table->string('status')->nullable();
            $table->text('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('rating', 10, 1)->default(0);
            $table->bigInteger('total_votes')->default(0);
            $table->bigInteger('view_total')->default(0);
            $table->bigInteger('view_day')->default(0);
            $table->bigInteger('view_week')->default(0);
            $table->bigInteger('view_month')->default(0);
            $table->timestamp('upview_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};
