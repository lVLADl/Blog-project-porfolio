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
        Schema::create('article_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->string('intro')->nullable();
            $table->text('map_url')->nullable();
            $table->json('itinerary_days')->nullable();
            $table->json('trip_budget')->nullable();
            $table->string('trip_budget_advice')->nullable();
            $table->string('results_title')->nullable();
            $table->string('results_description')->nullable();

            $table->timestamps();

            $table->unique(['article_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_itineraries');
    }
};
