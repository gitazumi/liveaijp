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
            $table->string('temp_status', 20)->nullable()->after('status');
        });

        DB::statement("UPDATE users SET temp_status = status");
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('status', 20)->default('unverified')->after('chatbot_token');
        });
        
        DB::statement("UPDATE users SET status = LOWER(temp_status)");
        
        DB::statement("UPDATE users SET status = 'unverified' WHERE email_verified_at IS NULL AND status = 'active'");
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('temp_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
