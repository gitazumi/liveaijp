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
        $columnType = DB::select("SHOW COLUMNS FROM users WHERE Field = 'status'")[0]->Type;
        
        DB::statement("ALTER TABLE users MODIFY COLUMN status VARCHAR(20) DEFAULT 'unverified'");
        
        DB::statement("UPDATE users SET status = 'active' WHERE status = 'Active'");
        DB::statement("UPDATE users SET status = 'inactive' WHERE status = 'Inactive'");
        
        DB::statement("UPDATE users SET status = 'unverified' WHERE email_verified_at IS NULL AND status = 'active'");
        
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('unverified', 'registered', 'active', 'inactive') DEFAULT 'unverified'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
