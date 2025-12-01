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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('作成者（管理者）');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->comment('カテゴリ');
            $table->string('title')->comment('タイトル');
            $table->string('slug')->unique()->comment('スラッグ（URL用）');
            $table->text('excerpt')->nullable()->comment('抜粋');
            $table->longText('content')->comment('本文');
            $table->string('featured_image')->nullable()->comment('アイキャッチ画像');
            $table->boolean('is_published')->default(false)->comment('公開状態');
            $table->timestamp('published_at')->nullable()->comment('公開日時');
            $table->integer('views')->default(0)->comment('閲覧数');
            $table->integer('order')->default(0)->comment('表示順序');
            $table->json('meta_keywords')->nullable()->comment('メタキーワード');
            $table->text('meta_description')->nullable()->comment('メタディスクリプション');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('is_published');
            $table->index('published_at');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
