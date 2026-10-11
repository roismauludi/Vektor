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
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('notifications', 'type')) {
                    $table->string('type')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'title')) {
                    $table->string('title')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'message')) {
                    $table->text('message')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'data')) {
                    $table->json('data')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'read_at')) {
                    $table->timestamp('read_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
