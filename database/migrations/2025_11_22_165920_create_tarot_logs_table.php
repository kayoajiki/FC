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
        Schema::create('tarot_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable()->index()->comment('非ログインユーザー用セッションID');
            $table->string('card_name')->comment('カード名');
            $table->string('card_image')->nullable()->comment('画像パス');
            $table->text('message')->comment('メッセージ');
            $table->string('position')->default('正位置')->comment('正位置/逆位置');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarot_logs');
    }
};
