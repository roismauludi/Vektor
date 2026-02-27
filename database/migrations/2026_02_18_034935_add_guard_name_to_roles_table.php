<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add guard_name column if it doesn't exist
        if (!Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('guard_name')->default('web')->after('name');
            });
            
            // Update existing roles to have guard_name = 'web'
            DB::table('roles')->update(['guard_name' => 'web']);
            
            // Add unique constraint on name and guard_name
            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique(['name']);
                $table->unique(['name', 'guard_name']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name', 'guard_name']);
            $table->unique(['name']);
            $table->dropColumn('guard_name');
        });
    }
};
