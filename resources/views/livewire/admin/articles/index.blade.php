<?php

use App\Models\Article;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $articles;

    public function mount(): void
    {
        $this->articles = Article::with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deleteArticle($id): void
    {
        Article::findOrFail($id)->delete();
        $this->mount(); // 再読み込み
        session()->flash('message', '記事を削除しました');
    }

    public function togglePublish($id): void
    {
        $article = Article::findOrFail($id);
        $article->is_published = !$article->is_published;
        if ($article->is_published && !$article->published_at) {
            $article->published_at = now();
        }
        $article->save();
        $this->mount(); // 再読み込み
        session()->flash('message', '記事の公開状態を更新しました');
    }
}; ?>

<div>
    <x-layouts.app>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-main">コラム管理（モック）</h1>
                <a href="{{ route('admin.articles.create') }}" 
                   class="px-4 py-2 bg-peach text-white rounded-lg font-semibold hover:bg-peach/90 transition-colors">
                    新規記事作成
                </a>
            </div>

            @if(session('message'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            <!-- 記事一覧テーブル -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">タイトル</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">カテゴリ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">公開状態</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">公開日</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">閲覧数</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($articles as $article)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-main">{{ $article->title }}</div>
                                    @if($article->slug)
                                        <div class="text-xs text-main/50">{{ $article->slug }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($article->category)
                                        <span class="px-2 py-1 text-xs font-semibold text-peach bg-peach/10 rounded">
                                            {{ $article->category->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-main/50">未分類</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        wire:click="togglePublish({{ $article->id }})"
                                        class="px-3 py-1 text-xs font-semibold rounded {{ $article->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $article->is_published ? '公開中' : '非公開' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-main/70">
                                    {{ $article->published_at ? $article->published_at->format('Y/m/d H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-main/70">
                                    {{ number_format($article->views) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" 
                                       class="text-peach hover:text-peach/80 mr-4">編集</a>
                                    <button 
                                        wire:click="deleteArticle({{ $article->id }})"
                                        onclick="return confirm('本当に削除しますか？')"
                                        class="text-red-600 hover:text-red-800">削除</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-main/50">
                                    記事がありません
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>モック画面です</strong><br>
                    実際の記事作成・編集機能は今後実装予定です。
                </p>
            </div>
        </div>
    </x-layouts.app>
</div>



