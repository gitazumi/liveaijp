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
        DB::statement("ALTER TABLE users MODIFY COLUMN status VARCHAR(20) DEFAULT 'unverified'");
        
        DB::statement("UPDATE users SET status = 'Active' WHERE status = 'active'");
        DB::statement("UPDATE users SET status = 'Inactive' WHERE status = 'inactive'");
        DB::statement("UPDATE users SET status = 'unverified' WHERE status = 'registered'");
        
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('Active', 'Inactive', 'unverified') DEFAULT 'unverified'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
