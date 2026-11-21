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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('lecture_sessions')->cascadeOnDelete();
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->string('type')->default('pdf'); // 'pdf', 'ppt', 'zip', 'link'
            $table->string('external_url')->nullable();
            $table->string('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
