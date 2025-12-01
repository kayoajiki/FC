<?php

use Livewire\Volt\Component;
use App\Services\Fortune\ZiweiService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('components.layouts.guest')]
#[Title('紫微斗数とは - Fortune Compass')]
class extends Component {
    public array $sampleResult = [];
    public string $pageDescription = '生年月日と出生時刻から、人生の12の領域を詳細に読み解く「東洋の占星術」。あなたの運命の縮図「命盤」が導き出す、驚きの真実。';

    public function mount(ZiweiService $service): void
    {
        $birthDate = Carbon::create(1990, 1, 1, 12, 0, 0);
        $this->sampleResult = $service->calculate($birthDate, '12:00');
        $this->sampleResult['star_placement']['hour_star'] = '紫微';
    }

    public function with(): array
    {
        return [
            'description' => $this->pageDescription,
            'ogType' => 'article',
            'ogTitle' => '紫微斗数とは - Fortune Compass',
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
                'headline' => '紫微斗数とは - 東洋の占星術で読み解く運命の縮図',
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
            ['label' => '紫微斗数とは']
        ]" />
        
        <header class="mb-12 text-center">
            <div class="inline-block px-3 py-1 mb-4 text-sm font-medium text-[#9F7AEA] bg-[#9F7AEA]/10 rounded-full">
                Zi Wei Dou Shu
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif;">
                紫微斗数とは
            </h1>
            <p class="text-xl text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 max-w-2xl mx-auto leading-relaxed">
                出生時刻が生み出す、あなただけの運命の縮図。<br>
                「東洋の占星術」で、人生のすべてを読み解く。
            </p>
        </header>

        <div class="space-y-20">
            <!-- イントロダクション -->
            <section class="prose prose-lg dark:prose-invert max-w-none">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3 border-b-2 border-[#9F7AEA]/30 pb-2">
                    <span class="text-3xl">🌌</span> 「出生時刻」が鍵となる、高精度の占術
                </h2>
                <p class="leading-loose">
                    紫微斗数（しびとすう）は、唐の時代から伝わる中国の占星術です。西洋占星術と同様に「出生時刻」を必要とするのが最大の特徴で、その精度の高さから、かつては皇帝のみが使用を許された秘伝とされていました。
                </p>
                <p class="leading-loose">
                    あなたの生まれた瞬間の星の配置を記した「命盤（めいばん）」を作成し、そこに配置される14の主星と多くの副星から、性格、才能、結婚、財運など、人生のあらゆる側面を詳細に分析します。
                </p>
            </section>

            <!-- わかること（6つのカテゴリ） -->
            <section>
                <h2 class="text-2xl font-bold mb-8 text-[#2A2E47] dark:text-[#FFFDF9] text-center">
                    紫微斗数でわかる6つのこと
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- 1. 性格・本質 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#9F7AEA]/20">
                        <div class="text-3xl mb-4">👤</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">性格・本質</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            「命宮」に入る星から、あなたの基本的な性格、容姿、才能、そして人生全体の傾向がわかります。
                        </p>
                    </div>
                    <!-- 2. 適性・才能 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#9F7AEA]/20">
                        <div class="text-3xl mb-4">✨</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">適性・才能</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            「官禄宮」を中心に分析し、適職や仕事での成功スタイル、リーダーシップの有無などを読み解きます。
                        </p>
                    </div>
                    <!-- 3. 運勢の流れ -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#9F7AEA]/20">
                        <div class="text-3xl mb-4">⏳</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">運勢の流れ</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            10年ごとの大運、1年ごとの流年運を詳細に予測し、チャンスの時期や注意すべき時期を知ることができます。
                        </p>
                    </div>
                    <!-- 4. 人間関係・相性 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#9F7AEA]/20">
                        <div class="text-3xl mb-4">🤝</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">人間関係・相性</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            「夫妻宮」「兄弟宮」「父母宮」などから、それぞれの対人関係の傾向や縁の深さを読み解きます。
                        </p>
                    </div>
                    <!-- 5. 職業・キャリア -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#9F7AEA]/20">
                        <div class="text-3xl mb-4">📈</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">職業・キャリア</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            どのような環境で能力を発揮できるか、独立に向いているか、組織で出世するタイプかなどが明確になります。
                        </p>
                    </div>
                    <!-- 6. 健康・体質 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#9F7AEA]/20">
                        <div class="text-3xl mb-4">🩺</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">健康・体質</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            「疾厄宮」から、体質的な弱点や注意すべき病気の傾向、ストレス耐性などを知ることができます。
                        </p>
                    </div>
                </div>
            </section>

            <!-- 詳しい解説（12宮など） -->
            <section class="bg-[#FFFDF9] dark:bg-[#2A2E47]/10 rounded-2xl p-8 border border-[#9F7AEA]/30">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3">
                    <span class="text-3xl">🏰</span> 人生を12に分ける「宮（きゅう）」
                </h2>
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1 prose dark:prose-invert">
                        <p>
                            紫微斗数の最大の特徴は、人生に関わる事柄を12のカテゴリー（宮）に分類することです。
                            これを「命盤」と呼ばれるチャートに配置し、どの宮にどの星が入るかで運勢を判断します。
                        </p>
                        <p>
                            最も重要なのが、自分自身を表す「<strong>命宮（めいきゅう）</strong>」です。
                            その他にも、金運を表す「財帛宮」、仕事をみる「官禄宮」、結婚をみる「夫妻宮」などがあり、
                            知りたいテーマに合わせて注目する宮を変えることで、驚くほど詳細な分析が可能になります。
                        </p>
                    </div>
                    <div class="flex-1 w-full max-w-md">
                        <!-- 12宮の簡易図 -->
                        <div class="grid grid-cols-4 gap-2 text-xs text-center">
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">巳<br>財帛</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">午<br>子女</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">未<br>夫妻</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">申<br>兄弟</div>
                            
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">辰<br>疾厄</div>
                            <div class="col-span-2 row-span-2 bg-[#9F7AEA]/5 rounded flex items-center justify-center text-[#9F7AEA] font-bold text-lg">命盤</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">酉<br>命宮</div>
                            
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">卯<br>遷移</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">戌<br>父母</div>
                            
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">寅<br>奴僕</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">丑<br>官禄</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">子<br>田宅</div>
                            <div class="bg-white dark:bg-[#2A2E47] p-2 rounded shadow border border-[#9F7AEA]/20 aspect-square flex items-center justify-center font-bold">亥<br>福徳</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- サンプル診断結果 -->
            <section>
                <div class="text-center mb-8">
                    <span class="text-sm font-semibold text-[#9F7AEA] tracking-wider">SAMPLE RESULT</span>
                    <h2 class="text-2xl font-bold mt-2 text-[#2A2E47] dark:text-[#FFFDF9]">診断結果のイメージ</h2>
                </div>

                <div class="bg-white dark:bg-[#2A2E47]/30 rounded-2xl shadow-lg border border-[#9F7AEA]/20 overflow-hidden">
                    <!-- ヘッダー -->
                    <div class="bg-[#9F7AEA]/10 p-6 text-center border-b border-[#9F7AEA]/20">
                        <h3 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9]">あなたの命盤</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mt-1">
                            中心となる星：{{ $sampleResult['star_placement']['hour_star'] ?? '紫微' }}
                        </p>
                    </div>

                    <div class="p-6 md:p-8 space-y-8">
                        <!-- 星の配置 -->
                        <div class="text-center">
                            <p class="text-sm font-medium text-[#2A2E47]/60 dark:text-[#FFFDF9]/60 mb-2">あなたを導く主星</p>
                            <div class="inline-block px-6 py-3 rounded-lg bg-gradient-to-r from-[#9F7AEA] to-[#B794F4] text-white text-xl font-bold shadow-md mx-auto mb-3">
                                {{ $sampleResult['star_placement']['hour_star'] ?? '紫微' }}星
                            </div>
                            <p class="text-[#2A2E47] dark:text-[#FFFDF9] font-medium max-w-lg mx-auto">
                                あなたの命宮には「{{ $sampleResult['star_placement']['hour_star'] ?? '紫微' }}星」が入っています。<br>
                                これは王様の星とも呼ばれ、気品と統率力を表します...（詳細な解説が表示されます）
                            </p>
                        </div>

                        <!-- 12宮の簡易グリッド表示 -->
                        <div>
                            <h4 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-4 text-center">
                                各宮の様子
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($sampleResult['palaces'] ?? [] as $index => $palace)
                                    @if($index < 4) <!-- 最初の4つだけ表示（サンプルなので） -->
                                        <div class="bg-white dark:bg-[#2A2E47]/50 p-3 rounded border border-[#9F7AEA]/20">
                                            <div class="text-xs text-[#9F7AEA] font-bold mb-1">{{ $palace['name'] }}</div>
                                            <div class="text-sm font-medium text-[#2A2E47] dark:text-[#FFFDF9]">
                                                {{ ['天機', '太陽', '武曲', '天同'][$index] ?? '' }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="col-span-2 md:col-span-4 text-center py-2 text-xs text-[#2A2E47]/50 dark:text-[#FFFDF9]/50 bg-[#9F7AEA]/5 rounded dashed border border-[#9F7AEA]/20">
                                    ... 他の8つの宮も詳細に分析されます
                                </div>
                            </div>
                        </div>

                        <!-- 強みと注意点 -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-100 dark:border-purple-800/30">
                                <h4 class="font-bold text-purple-800 dark:text-purple-300 mb-2 flex items-center gap-2">
                                    <span>💎</span> あなたの強み
                                </h4>
                                <ul class="list-disc list-inside text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 space-y-1">
                                    @foreach($sampleResult['strengths'] ?? [] as $strength)
                                        <li>{{ $strength }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="bg-pink-50 dark:bg-pink-900/20 p-4 rounded-lg border border-pink-100 dark:border-pink-800/30">
                                <h4 class="font-bold text-pink-800 dark:text-pink-300 mb-2 flex items-center gap-2">
                                    <span>⚠️</span> 注意点
                                </h4>
                                <p class="text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80">
                                    {{ $sampleResult['caution'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <div class="text-center bg-gradient-to-r from-[#9F7AEA]/10 to-[#B794F4]/10 rounded-3xl p-10 border border-[#9F7AEA]/20">
                <h2 class="text-3xl font-bold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]">
                    あなたの運命の縮図を見てみませんか？
                </h2>
                <p class="text-lg text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 mb-8">
                    出生時刻がわかる方は、ぜひ一度診断してみてください。<br>
                    驚くほど詳細な結果があなたを待っています。
                </p>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#9F7AEA] to-[#B794F4] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
                        ダッシュボードへ移動
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#9F7AEA] to-[#B794F4] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
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