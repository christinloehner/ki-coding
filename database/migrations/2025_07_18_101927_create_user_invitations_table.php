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
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('token', 64)->unique();
            $table->string('name')->nullable();
            $table->foreignId('invited_by')->constrained('users');
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['token', 'expires_at']);
            $table->index(['email', 'accepted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_invitations');
    }
};