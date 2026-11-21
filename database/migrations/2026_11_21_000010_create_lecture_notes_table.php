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
        Schema::create('lecture_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('lecture_sessions')->cascadeOnDelete();
            $table->string('title');
            $table->string('note_type')->default('summary');
            $table->json('key_takeaways')->nullable();
            $table->longText('content');
            $table->text('student_notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_notes');
    }
};
