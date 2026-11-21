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
        Schema::create('watch_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recording_id')->constrained('recordings')->cascadeOnDelete();
            $table->unsignedInteger('progress_seconds')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('last_watched_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'recording_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_histories');
    }
};
