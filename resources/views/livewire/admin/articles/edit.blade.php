<?php

use App\Models\Article;
use App\Models\Category;
use Livewire\Volt\Component;

new class extends Component
{
    public Article $article;
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $category_id = null;
    public $is_published = false;
    public $published_at = null;

    public function mount($id): void
    {
        $this->article = Article::findOrFail($id);
        $this->title = $this->article->title;
        $this->slug = $this->article->slug;
        $this->excerpt = $this->article->excerpt ?? '';
        $this->content = $this->article->content;
        $this->category_id = $this->article->category_id;
        $this->is_published = $this->article->is_published;
        $this->published_at = $this->article->published_at?->format('Y-m-d\TH:i');
    }

    public function save(): void
    {
        session()->flash('message', '記事更新機能は今後実装予定です（モック画面）');
        $this->redirect(route('admin.articles.index'));
    }
}; ?>

<div>
    <x-layouts.app>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <a href="{{ route('admin.articles.index') }}" 
                   class="text-peach hover:text-peach/80 inline-flex items-center gap-2">
                    ← 記事一覧に戻る
                </a>
                <h1 class="text-3xl font-bold text-main mt-4">記事編集（モック）</h1>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-8">
                <form wire:submit.prevent="save">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-main mb-2">タイトル *</label>
                            <input type="text" 
                                   wire:model="title" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-peach focus:ring-2 focus:ring-peach/20 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-main mb-2">スラッグ</label>
                            <input type="text" 
                                   wire:model="slug" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-peach focus:ring-2 focus:ring-peach/20 outline-none">
                            <p class="text-xs text-main/50 mt-1">未入力の場合はタイトルから自動生成されます</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-main mb-2">抜粋</label>
                            <textarea wire:model="excerpt" 
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-peach focus:ring-2 focus:ring-peach/20 outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-main mb-2">本文 *</label>
                            <textarea wire:model="content" 
                                      rows="10"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-peach focus:ring-2 focus:ring-peach/20 outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-main mb-2">カテゴリ</label>
                            <select wire:model="category_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-peach focus:ring-2 focus:ring-peach/20 outline-none">
                                <option value="">選択してください</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="is_published" class="form-checkbox text-peach">
                                <span class="text-sm font-medium text-main">公開する</span>
                            </label>
                        </div>

                        @if($is_published)
                            <div>
                                <label class="block text-sm font-medium text-main mb-2">公開日時</label>
                                <input type="datetime-local" 
                                       wire:model="published_at" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-peach focus:ring-2 focus:ring-peach/20 outline-none">
                            </div>
                        @endif

                        <div class="flex gap-4">
                            <button type="submit" 
                                    class="px-6 py-3 bg-peach text-white rounded-lg font-semibold hover:bg-peach/90 transition-colors">
                                更新
                            </button>
                            <a href="{{ route('admin.articles.index') }}" 
                               class="px-6 py-3 border border-gray-300 text-main rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                キャンセル
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>モック画面です</strong><br>
                    実際の記事更新機能は今後実装予定です。
                </p>
            </div>
        </div>
    </x-layouts.app>
</div>



