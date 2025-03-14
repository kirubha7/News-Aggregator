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
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('author')->nullable();
            $table->string('url')->nullable();
            $table->string('image_url')->nullable();
            $table->uuid('source_id')->constrained('sources')->onDelete('cascade');
            $table->uuid('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->timestamp('published_at')->nullable();
            $table->integer('status')->default(1);
            $table->integer('view_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->index(['title', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
