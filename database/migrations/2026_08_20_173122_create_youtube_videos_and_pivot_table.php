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
        Schema::create('youtube_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('youtube_url');
            $table->string('youtube_id')->nullable();
            $table->text('thumbnail')->nullable();
            $table->string('duration')->nullable(); // e.g. "12:45"
            $table->string('views_text')->nullable(); // e.g. "45K views"
            $table->text('description')->nullable();
            $table->boolean('is_hero')->default(false); // Highlight in Home Hero
            $table->boolean('is_trending')->default(false); // Highlight in Collections Trending
            $table->boolean('is_lookbook')->default(true); // Highlight in Home Lookbook Grid
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_youtube_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youtube_video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_youtube_video');
        Schema::dropIfExists('youtube_videos');
    }
};
