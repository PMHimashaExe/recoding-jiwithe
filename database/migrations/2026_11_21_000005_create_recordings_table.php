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
        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('lecture_sessions')->cascadeOnDelete();
            $table->unsignedInteger('part_number')->default(1);
            $table->string('video_path');
            $table->string('original_filename')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->string('duration')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->timestamps();

            // Constraint specified in prompt: session_id + part_number must be unique
            $table->unique(['session_id', 'part_number']);
            $table->index(['status', 'uploaded_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordings');
    }
};
