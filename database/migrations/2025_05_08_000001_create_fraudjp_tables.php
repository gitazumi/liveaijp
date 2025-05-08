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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->text('content')->comment('通報内容');
            $table->string('phone_number')->nullable()->comment('電話番号');
            $table->string('url')->nullable()->comment('URL');
            $table->enum('status', ['on', 'off'])->default('on')->comment('表示ステータス');
            $table->timestamps();
        });

        Schema::create('fraudjp_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->comment('セッションID');
            $table->text('message')->comment('メッセージ内容');
            $table->boolean('is_user')->default(false)->comment('ユーザーメッセージかどうか');
            $table->boolean('db_referenced')->default(false)->comment('DBを参照したかどうか');
            $table->timestamps();
            
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('fraudjp_messages');
    }
};
