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
        Schema::table('articles', function (Blueprint $table) {
            $table->timestamp('deletion_requested_at')->nullable()->after('published_at');
            $table->string('deletion_reason')->nullable()->after('deletion_requested_at');
            $table->unsignedBigInteger('deletion_requested_by')->nullable()->after('deletion_reason');
            
            $table->foreign('deletion_requested_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['deletion_requested_by']);
            $table->dropColumn(['deletion_requested_at', 'deletion_reason', 'deletion_requested_by']);
        });
    }
};
