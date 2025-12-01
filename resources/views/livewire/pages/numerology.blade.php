<?php

use Livewire\Volt\Component;
use App\Services\Fortune\NumerologyService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('components.layouts.guest')]
#[Title('数秘術とは - Fortune Compass')]
class extends Component {
    public array $sampleResult = [];
    public string $pageDescription = '生年月日の数字に秘められた暗号を解き明かす数秘術。カバラの智慧に基づくライフパスナンバーが、あなたの人生の地図を描き出します。';

    public function mount(NumerologyService $service): void
    {
        $birthDate = Carbon::create(1990, 1, 1);
        $this->sampleResult = $service->calculate($birthDate);
    }

    public function with(): array
    {
        return [
            'description' => $this->pageDescription,
            'ogType' => 'article',
            'ogTitle' => '数秘術とは - Fortune Compass',
            'ogDescription' => $this->pageDescription,
        ];
    }
}; ?>

<div class="pt-0">
    @push('head-scripts')
        @php
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => '数秘術とは - 数字に秘められた人生の地図',
                'description' => $description,
                'author' => [
                    '@type' => 'Person',
                    'name' => 'Fortune Compass',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Fortune Compass',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/logo.png'),
                    ],
                ],
                'datePublished' => '2024-01-01',
                'dateModified' => now()->format('Y-m-d'),
            ];
        @endphp
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush

    <article class="max-w-4xl mx-auto px-4 py-12">
        <x-breadcrumbs :items="[
            ['label' => 'ホーム', 'url' => route('home')],
            ['label' => '数秘術とは']
        ]" />
        
        <header class="mb-12 text-center">
            <div class="inline-block px-3 py-1 mb-4 text-sm font-medium text-[#4FD1C5] bg-[#4FD1C5]/10 rounded-full">
                Numerology / Kabbalah
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif;">
                数秘術とは
            </h1>
            <p class="text-xl text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 max-w-2xl mx-auto leading-relaxed">
                数字は、宇宙共通の言語。<br>
                生年月日に隠された暗号が、あなたの魂の目的を語りかける。
            </p>
        </header>

        <div class="space-y-20">
            <!-- イントロダクション -->
            <section class="prose prose-lg dark:prose-invert max-w-none">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3 border-b-2 border-[#4FD1C5]/30 pb-2">
                    <span class="text-3xl">🔢</span> 数字に宿る神秘と「カバラ」の智慧
                </h2>
                <p class="leading-loose">
                    数秘術（ヌメロロジー）は、古代ギリシャの数学者ピタゴラスが体系化したと言われる、「数」の神秘を探求する学問です。中でも「カバラ数秘術」は、ユダヤ教の神秘主義思想「カバラ」の智慧と融合し、単なる性格診断を超えた、魂の成長プロセスや人生の使命（ライフパス）を解き明かすツールとして発展しました。
                </p>
                <p class="leading-loose">
                    あなたの生年月日という、変えることのできない数字には、あなたがこの世に生まれてきた意味や、魂が約束してきた「人生の地図」が記されています。
                </p>
            </section>

            <!-- わかること（6つのカテゴリ） -->
            <section>
                <h2 class="text-2xl font-bold mb-8 text-[#2A2E47] dark:text-[#FFFDF9] text-center">
                    数秘術でわかる6つのこと
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- 1. 性格・本質 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#4FD1C5]/20">
                        <div class="text-3xl mb-4">🧠</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">性格・本質</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            「ライフパスナンバー」から、あなたの基本的な性格、思考パターン、行動の癖などがわかります。
                        </p>
                    </div>
                    <!-- 2. 適性・才能 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#4FD1C5]/20">
                        <div class="text-3xl mb-4">💎</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">適性・才能</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            生まれ持ったギフト（才能）や、それを活かして社会に貢献する方法が明確になります。
                        </p>
                    </div>
                    <!-- 3. 運勢の流れ -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#4FD1C5]/20">
                        <div class="text-3xl mb-4">🌊</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">運勢の流れ</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            「個人年サイクル」を計算し、1年ごとのテーマや、種まきの時期・収穫の時期などを知ることができます。
                        </p>
                    </div>
                    <!-- 4. 人間関係・相性 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#4FD1C5]/20">
                        <div class="text-3xl mb-4">💞</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">人間関係・相性</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            お互いの数字の性質を知ることで、理解し合うためのヒントや、補い合える関係性がわかります。
                        </p>
                    </div>
                    <!-- 5. 職業・キャリア -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#4FD1C5]/20">
                        <div class="text-3xl mb-4">🚀</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">職業・キャリア</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            あなたの魂が喜びを感じる働き方や、天職に近づくためのキャリアパスが見えてきます。
                        </p>
                    </div>
                    <!-- 6. 健康・体質 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#4FD1C5]/20">
                        <div class="text-3xl mb-4">🧘</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">健康・体質</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            数字が持つエネルギーの偏りから、ストレスを感じやすい状況や、リフレッシュ方法を知ることができます。
                        </p>
                    </div>
                </div>
            </section>

            <!-- 詳しい解説（カバラとライフパスナンバー） -->
            <section class="bg-[#FFFDF9] dark:bg-[#2A2E47]/10 rounded-2xl p-8 border border-[#4FD1C5]/30">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3">
                    <span class="text-3xl">✡️</span> 魂の設計図「カバラ数秘術」
                </h2>
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1 prose dark:prose-invert">
                        <p>
                            数秘術にはいくつかの流派がありますが、Fortune Compassでは「カバラ数秘術」を採用しています。
                            カバラとは「受け取る」という意味のヘブライ語で、宇宙の真理を受け取るための智慧とされています。
                        </p>
                        <p>
                            カバラ数秘術では、1から9までのルート・ナンバーに加え、11、22、33という「マスターナンバー」を重視します。
                            これらは特別な霊的エネルギーを持つ数字とされ、強烈な直感力や、大きな使命を表すとされています。
                        </p>
                    </div>
                    <div class="flex-1 w-full max-w-md">
                        <div class="bg-white dark:bg-[#2A2E47] p-6 rounded-xl shadow-inner text-center">
                            <h4 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-4">計算方法の例</h4>
                            <div class="text-2xl font-mono text-[#4FD1C5] mb-2">1990年1月1日</div>
                            <div class="flex justify-center items-center gap-2 text-gray-500 dark:text-gray-400 mb-4">
                                <span>1+9+9+0</span>
                                <span>+</span>
                                <span>1</span>
                                <span>+</span>
                                <span>1</span>
                            </div>
                            <div class="text-lg text-[#2A2E47] dark:text-[#FFFDF9] mb-2">
                                = 19 + 1 + 1 = 21
                            </div>
                            <div class="text-lg text-[#2A2E47] dark:text-[#FFFDF9]">
                                = 2 + 1 = <span class="text-3xl font-bold text-[#4FD1C5]">3</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-4">
                                ※ すべての数字を足し合わせ、1桁になるまで（または11,22,33になるまで）計算します。
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- サンプル診断結果 -->
            <section>
                <div class="text-center mb-8">
                    <span class="text-sm font-semibold text-[#4FD1C5] tracking-wider">SAMPLE RESULT</span>
                    <h2 class="text-2xl font-bold mt-2 text-[#2A2E47] dark:text-[#FFFDF9]">診断結果のイメージ</h2>
                    <p class="text-sm text-[#2A2E47]/60 dark:text-[#FFFDF9]/60 mt-2">
                        1990年1月1日生まれの場合
                    </p>
                </div>

                <div class="bg-white dark:bg-[#2A2E47]/30 rounded-2xl shadow-lg border border-[#4FD1C5]/20 overflow-hidden">
                    <!-- ヘッダー -->
                    <div class="bg-[#4FD1C5]/10 p-6 text-center border-b border-[#4FD1C5]/20">
                        <h3 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9]">あなたのライフパスナンバー</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mt-1">
                            人生のメインテーマを表す数字
                        </p>
                    </div>

                    <div class="p-6 md:p-8 space-y-8">
                        <!-- ライフパスナンバー -->
                        <div class="text-center">
                            <div class="inline-block w-32 h-32 rounded-full bg-gradient-to-br from-[#4FD1C5] to-[#81E6D9] text-white text-6xl font-bold flex items-center justify-center mb-6 shadow-lg mx-auto">
                                {{ $sampleResult['life_path_number'] }}
                            </div>
                            <h4 class="text-xl font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-2">
                                {{ $sampleResult['characteristics'] }}
                            </h4>
                            <p class="text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 font-medium max-w-lg mx-auto">
                                数字の「{{ $sampleResult['life_path_number'] }}」は、創造性と自己表現を象徴します。<br>
                                あなたは生まれながらのエンターテイナーであり...（詳細な解説が表示されます）
                            </p>
                        </div>

                        <!-- 強みと注意点 -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-teal-50 dark:bg-teal-900/20 p-4 rounded-lg border border-teal-100 dark:border-teal-800/30">
                                <h4 class="font-bold text-teal-800 dark:text-teal-300 mb-2 flex items-center gap-2">
                                    <span>💎</span> あなたの強み
                                </h4>
                                <ul class="list-disc list-inside text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 space-y-1">
                                    @foreach($sampleResult['strengths'] as $strength)
                                        <li>{{ $strength }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg border border-orange-100 dark:border-orange-800/30">
                                <h4 class="font-bold text-orange-800 dark:text-orange-300 mb-2 flex items-center gap-2">
                                    <span>⚠️</span> 注意点
                                </h4>
                                <p class="text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80">
                                    {{ $sampleResult['caution'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <div class="text-center bg-gradient-to-r from-[#4FD1C5]/10 to-[#81E6D9]/10 rounded-3xl p-10 border border-[#4FD1C5]/20">
                <h2 class="text-3xl font-bold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]">
                    あなたの数字を知りたくありませんか？
                </h2>
                <p class="text-lg text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 mb-8">
                    生年月日を入力するだけで、あなたの魂の地図が広がります。<br>
                    Fortune Compassで、自分自身を再発見しましょう。
                </p>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#4FD1C5] to-[#81E6D9] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
                        ダッシュボードへ移動
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#4FD1C5] to-[#81E6D9] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
                            無料で診断する
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-[#2A2E47] dark:text-[#FFFDF9] transition-all duration-200 bg-white dark:bg-[#2A2E47] border-2 border-[#2A2E47]/10 dark:border-[#FFFDF9]/10 rounded-full hover:bg-[#2A2E47]/5 dark:hover:bg-[#FFFDF9]/5">
                            ログイン
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </article>
</div>