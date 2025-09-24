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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('thumbnail');
            $table->string('skill_level');
            $table->text('about');

            // Tambahkan kolom category_id terlebih dahulu
            $table->unsignedBigInteger('category_id');

            $table->unsignedBigInteger('budget');

            // Kolom client_id sudah ada
            $table->unsignedBigInteger('client_id');

            $table->boolean('has_finished');
            $table->boolean('has_started');
            $table->softDeletes();
            $table->timestamps();

            // Lalu, definisikan foreign key secara manual
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
