<?php

use App\Models\Article;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public ?string $categorySlug = null;
    public ?string $search = null;

    public function mount(?string $category = null): void
    {
        $this->categorySlug = $category;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function getArticlesProperty()
    {
        $query = Article::with(['category', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc');

        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return Category::withCount('articles')
            ->has('articles')
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }
}; ?>

<div>
    <x-layouts.guest>
        <div class="min-h-screen bg-base">
            <!-- ヘッダー -->
            <header class="bg-main/5 py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl font-bold text-main mb-4">コラム</h1>
                    <p class="text-lg text-main/70">占いや運勢に関する記事をお届けします</p>
                </div>
            </header>

            <!-- メインコンテンツ -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- サイドバー -->
                    <aside class="lg:col-span-1">
                        <!-- カテゴリ -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h2 class="text-xl font-semibold text-main mb-4">カテゴリ</h2>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('column.index') }}" 
                                       class="block px-3 py-2 rounded-lg hover:bg-peach/10 transition-colors {{ !$this->categorySlug ? 'bg-peach/20 text-peach' : 'text-main/70' }}">
                                        すべて
                                    </a>
                                </li>
                                @foreach($this->categories as $category)
                                    <li>
                                        <a href="{{ route('column.index', ['category' => $category->slug]) }}" 
                                           class="block px-3 py-2 rounded-lg hover:bg-peach/10 transition-colors {{ $this->categorySlug === $category->slug ? 'bg-peach/20 text-peach' : 'text-main/70' }}">
                                            {{ $category->name }}
                                            <span class="text-sm text-main/50">({{ $category->articles_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>

                    <!-- 記事一覧 -->
                    <div class="lg:col-span-3">
                        <!-- 検索 -->
                        <div class="mb-6">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search" 
                                   placeholder="記事を検索..." 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-peach focus:ring-2 focus:ring-peach/20 outline-none">
                        </div>

                        <!-- 記事グリッド -->
                        @if($this->articles->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                @foreach($this->articles as $article)
                                    <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                        @if($article->featured_image)
                                            <a href="{{ route('column.show', $article->slug) }}">
                                                <img src="{{ asset($article->featured_image) }}" 
                                                     alt="{{ $article->title }}" 
                                                     class="w-full h-48 object-cover">
                                            </a>
                                        @endif
                                        <div class="p-6">
                                            @if($article->category)
                                                <a href="{{ route('column.index', ['category' => $article->category->slug]) }}" 
                                                   class="inline-block px-3 py-1 text-xs font-semibold text-peach bg-peach/10 rounded-full mb-3">
                                                    {{ $article->category->name }}
                                                </a>
                                            @endif
                                            <h2 class="text-xl font-semibold text-main mb-2">
                                                <a href="{{ route('column.show', $article->slug) }}" 
                                                   class="hover:text-peach transition-colors">
                                                    {{ $article->title }}
                                                </a>
                                            </h2>
                                            @if($article->excerpt)
                                                <p class="text-main/70 mb-4 line-clamp-2">{{ $article->excerpt }}</p>
                                            @endif
                                            <div class="flex items-center justify-between text-sm text-main/50">
                                                <time datetime="{{ $article->published_at->format('Y-m-d') }}">
                                                    {{ $article->published_at->format('Y年m月d日') }}
                                                </time>
                                                <span>{{ $article->views }} views</span>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                            <!-- ページネーション -->
                            <div class="mt-8">
                                {{ $this->articles->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <p class="text-main/70 text-lg">記事が見つかりませんでした</p>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </x-layouts.guest>
</div>



