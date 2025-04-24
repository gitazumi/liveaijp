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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status', 20)->default('unverified')->change();
        });

        DB::statement("UPDATE users SET status = LOWER(status)");
        
        DB::statement("UPDATE users SET status = 'unverified' WHERE email_verified_at IS NULL");
        
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('unverified', 'registered', 'active', 'inactive') DEFAULT 'unverified'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->change();
        });
    }
};
