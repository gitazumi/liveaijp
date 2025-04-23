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
        DB::statement("UPDATE users SET status = 'active' WHERE status = 'Active'");
        DB::statement("UPDATE users SET status = 'inactive' WHERE status = 'Inactive'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE users SET status = 'Active' WHERE status = 'active'");
        DB::statement("UPDATE users SET status = 'Inactive' WHERE status = 'inactive'");
    }
};
