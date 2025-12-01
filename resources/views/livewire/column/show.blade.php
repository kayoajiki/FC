<?php

use App\Models\Article;
use Livewire\Volt\Component;

new class extends Component
{
    public Article $article;
    public $relatedArticles;

    public function mount(string $slug): void
    {
        $this->article = Article::with(['category', 'tags', 'user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // 閲覧数を増やす
        $this->article->incrementViews();

        // 関連記事を取得
        $this->relatedArticles = $this->article->getRelatedArticles(5);
    }
}; ?>

<div>
    <x-layouts.guest>
        <div class="min-h-screen bg-base">
            <!-- パンくずリスト -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                <x-breadcrumbs :items="[
                    'ホーム' => route('home'),
                    'コラム' => route('column.index'),
                    $article->title => null,
                ]" />
            </div>

            <!-- メインコンテンツ -->
            <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- アイキャッチ画像 -->
                    @if($article->featured_image)
                        <img src="{{ asset($article->featured_image) }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-64 md:h-96 object-cover">
                    @endif

                    <div class="p-8 md:p-12">
                        <!-- カテゴリ・公開日 -->
                        <div class="flex items-center gap-4 mb-6">
                            @if($article->category)
                                <a href="{{ route('column.index', ['category' => $article->category->slug]) }}" 
                                   class="inline-block px-4 py-2 text-sm font-semibold text-peach bg-peach/10 rounded-full">
                                    {{ $article->category->name }}
                                </a>
                            @endif
                            <time datetime="{{ $article->published_at->format('Y-m-d') }}" 
                                  class="text-main/50 text-sm">
                                {{ $article->published_at->format('Y年m月d日') }}
                            </time>
                            <span class="text-main/50 text-sm">{{ $article->views }} views</span>
                        </div>

                        <!-- タイトル -->
                        <h1 class="text-3xl md:text-4xl font-bold text-main mb-6">
                            {{ $article->title }}
                        </h1>

                        <!-- 抜粋 -->
                        @if($article->excerpt)
                            <p class="text-xl text-main/70 mb-8 leading-relaxed">
                                {{ $article->excerpt }}
                            </p>
                        @endif

                        <!-- タグ -->
                        @if($article->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-2 mb-8">
                                @foreach($article->tags as $tag)
                                    <span class="px-3 py-1 text-sm text-main/70 bg-main/5 rounded-full">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <!-- 本文 -->
                        <div class="prose prose-lg max-w-none mb-12">
                            {!! app(\App\Services\Article\InternalLinkService::class)->generateLinks(nl2br(e($article->content)), $article) !!}
                        </div>

                        <!-- ログインCTA -->
                        @guest
                            <div class="bg-peach/10 border border-peach/30 rounded-lg p-6 mb-12">
                                <h3 class="text-xl font-semibold text-main mb-3">
                                    あなたの運勢を毎日チェック
                                </h3>
                                <p class="text-main/70 mb-4">
                                    Fortune Compassに登録すると、毎日の運勢やタロットカードを無料で利用できます。
                                </p>
                                <div class="flex gap-4">
                                    <a href="{{ route('register') }}" 
                                       class="px-6 py-3 bg-peach text-white rounded-lg font-semibold hover:bg-peach/90 transition-colors">
                                        無料で始める
                                    </a>
                                    <a href="{{ route('login') }}" 
                                       class="px-6 py-3 border border-peach text-peach rounded-lg font-semibold hover:bg-peach/10 transition-colors">
                                        ログイン
                                    </a>
                                </div>
                            </div>
                        @endguest

                        <!-- 関連記事 -->
                        @if($relatedArticles->isNotEmpty())
                            <div class="border-t border-gray-200 pt-8">
                                <h2 class="text-2xl font-semibold text-main mb-6">関連記事</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($relatedArticles as $related)
                                        <a href="{{ route('column.show', $related->slug) }}" 
                                           class="block p-4 bg-main/5 rounded-lg hover:bg-peach/10 transition-colors">
                                            <h3 class="font-semibold text-main mb-2">{{ $related->title }}</h3>
                                            @if($related->excerpt)
                                                <p class="text-sm text-main/70 line-clamp-2">{{ $related->excerpt }}</p>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </article>
            </main>
        </div>
    </x-layouts.guest>
</div>

