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
            $table->json('scam_types')->comment('チェックボックス複数選択結果');
            $table->text('description')->comment('通報本文');
            $table->json('evidence_files')->nullable()->comment('証拠ファイルのパス（複数対応）');
            $table->char('edit_token', 36)->unique()->comment('UUID');
            $table->string('ip_address', 45)->nullable()->comment('投稿者IP');
            $table->text('user_agent')->nullable()->comment('ブラウザ情報');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
