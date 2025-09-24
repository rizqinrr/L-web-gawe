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
    Schema::create('project_applicants', function (Blueprint $table) {
        $table->id();
        
        // Perbaiki baris ini
        $table->foreignId('project_id')->constrained()->onDelete('cascade');

        // Gunakan foreignId() juga untuk freelancer_id untuk konsistensi
        $table->foreignId('freelancer_id')->constrained('users')->onDelete('cascade');
        
        $table->text('message');
        $table->string('status');
        $table->softDeletes();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_applicants');
    }
};
