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
        Schema::create('sources', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID Primary Key
            $table->string('name')->unique();
            $table->string('url')->unique();
            $table->string('api_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->integer('status')->default(1);
            $table->json('default_params');
            $table->softDeletes(); // Enable soft delete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
