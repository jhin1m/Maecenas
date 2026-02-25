<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Comics: indexes for sorting/filtering columns used in ORDER BY and WHERE
        Schema::table('comics', function (Blueprint $table) {
            $table->index('view_total');
            $table->index('view_day');
            $table->index('view_week');
            $table->index('view_month');
            $table->index('status');
            $table->index('updated_at');
            $table->index('is_hot');
        });

        // Chapters: composite indexes for common query patterns
        Schema::table('chapters', function (Blueprint $table) {
            $table->index(['comic_id', 'chapter_number']);
            $table->index(['comic_id', 'slug']);
            $table->index('has_report');
        });

        // Chapterimgs: index for ordered page loading
        Schema::table('chapterimgs', function (Blueprint $table) {
            $table->index(['chapter_id', 'page']);
        });

        // Comments: indexes for filtered queries
        Schema::table('comments', function (Blueprint $table) {
            $table->index(['comic_id', 'parent_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        // Follows: composite index for user+comic lookups
        Schema::table('follows', function (Blueprint $table) {
            $table->unique(['user_id', 'comic_id']);
        });

        // Histories: composite index for user+comic lookups
        Schema::table('histories', function (Blueprint $table) {
            $table->unique(['user_id', 'comic_id']);
        });

        // Voting: composite index for user+comic lookups
        Schema::table('voting', function (Blueprint $table) {
            $table->unique(['user_id', 'comic_id']);
        });

        // Comic categories: composite index
        Schema::table('comic_categories', function (Blueprint $table) {
            $table->unique(['comic_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropIndex(['view_total']);
            $table->dropIndex(['view_day']);
            $table->dropIndex(['view_week']);
            $table->dropIndex(['view_month']);
            $table->dropIndex(['status']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['is_hot']);
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropIndex(['comic_id', 'chapter_number']);
            $table->dropIndex(['comic_id', 'slug']);
            $table->dropIndex(['has_report']);
        });

        Schema::table('chapterimgs', function (Blueprint $table) {
            $table->dropIndex(['chapter_id', 'page']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['comic_id', 'parent_id', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'comic_id']);
        });

        Schema::table('histories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'comic_id']);
        });

        Schema::table('voting', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'comic_id']);
        });

        Schema::table('comic_categories', function (Blueprint $table) {
            $table->dropUnique(['comic_id', 'category_id']);
        });
    }
};
