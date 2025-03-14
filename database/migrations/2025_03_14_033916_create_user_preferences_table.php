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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_preferred_authors', function (Blueprint $table) {
            $table->string('author');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'author']);
        });

        Schema::create('user_preferred_categories', function (Blueprint $table) {
            $table->uuid('category_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'category_id']);
        });

        Schema::create('user_preferred_sources', function (Blueprint $table) {
            $table->uuid('source_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
