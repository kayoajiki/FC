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
        Schema::create('fortune_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable()->index()->comment('非ログインユーザー用セッションID');
            $table->date('birth_date');
            $table->time('birth_time')->nullable();
            $table->string('birth_place')->nullable();
            $table->json('four_pillars_result')->nullable()->comment('四柱推命結果');
            $table->json('numerology_result')->nullable()->comment('数秘術結果');
            $table->json('ziwei_result')->nullable()->comment('紫微斗数結果（出生時間が必要）');
            $table->json('tarot_result')->nullable()->comment('タロット結果');
            $table->date('calculated_at')->index()->comment('計算日');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fortune_summaries');
    }
};
