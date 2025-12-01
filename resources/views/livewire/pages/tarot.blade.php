<?php

use Livewire\Volt\Component;
use App\Services\Fortune\TarotService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('components.layouts.guest')]
#[Title('タロットとは - Fortune Compass')]
class extends Component {
    public array $sampleResult = [];
    public array $positions = [];
    public string $pageDescription = '78枚のカードが映し出す、あなたの深層心理と未来。シンプルなスリーカードで、今の状況と次の一歩をやさしく整理します。';

    public function mount(TarotService $service): void
    {
        $this->sampleResult = $service->drawSpread(3);
        $this->positions = [
            ['name' => '過去', 'desc' => 'これまでの経緯や背景'],
            ['name' => '現在', 'desc' => '今起きていること・心の状態'],
            ['name' => '未来', 'desc' => 'この先の兆し・行動のヒント'],
        ];
    }

    public function with(): array
    {
        return [
            'description' => $this->pageDescription,
            'ogType' => 'article',
            'ogTitle' => 'タロットとは - Fortune Compass',
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
                'headline' => 'タロットとは - 78枚のカードが語る未来の物語',
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

    @php
        $positions = $this->positions;
    @endphp

    <article class="max-w-4xl mx-auto px-4 py-12">
        <x-breadcrumbs :items="[
            ['label' => 'ホーム', 'url' => route('home')],
            ['label' => 'タロットとは']
        ]" />
        
        <header class="mb-12 text-center">
            <div class="inline-block px-3 py-1 mb-4 text-sm font-medium text-[#F687B3] bg-[#F687B3]/10 rounded-full">
                Tarot Reading
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif;">
                タロットとは
            </h1>
            <p class="text-xl text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 max-w-2xl mx-auto leading-relaxed">
                偶然の一枚が、必然の答えを導き出す。<br>
                深層心理を映し出す鏡として、迷える心に光を灯す。
            </p>
        </header>

        <div class="space-y-20">
            <!-- イントロダクション -->
            <section class="prose prose-lg dark:prose-invert max-w-none">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3 border-b-2 border-[#F687B3]/30 pb-2">
                    <span class="text-3xl">🃏</span> 潜在意識とつながる「シンクロニシティ」
                </h2>
                <p class="leading-loose">
                    タロット占いは、無作為に引いたカードから意味を読み取る「卜（ぼく）術」の一種です。
                    心理学者ユングが提唱した「シンクロニシティ（意味のある偶然の一致）」の原理に基づき、偶然引いたカードには、あなたの潜在意識や現状が必然的に反映されると考えられています。
                </p>
                <p class="leading-loose">
                    78枚の美しいカードたちは、言葉にならない感情や、自分では気づいていない心の奥底を映し出す「鏡」のような存在です。
                    迷いの中にいる時、カードは客観的な視点を与え、前へ進むためのヒントを教えてくれます。
                </p>
            </section>

            <!-- わかること（6つのカテゴリ） -->
            <section>
                <h2 class="text-2xl font-bold mb-8 text-[#2A2E47] dark:text-[#FFFDF9] text-center">
                    タロットでわかる6つのこと
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- 1. 現状の把握 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#F687B3]/20">
                        <div class="text-3xl mb-4">📍</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">現状の把握</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            今、自分が置かれている状況や、問題の本質を客観的に理解することができます。
                        </p>
                    </div>
                    <!-- 2. 相手の気持ち -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#F687B3]/20">
                        <div class="text-3xl mb-4">❤️</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">相手の気持ち</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            気になる相手があなたをどう思っているか、深層心理や態度の裏側にある感情を推察します。
                        </p>
                    </div>
                    <!-- 3. 近い未来 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#F687B3]/20">
                        <div class="text-3xl mb-4">🔮</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">近い未来</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            このまま進んだ場合に起こりうる展開や、3ヶ月〜半年程度の短期的な未来を予測します。
                        </p>
                    </div>
                    <!-- 4. 選択の指針 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#F687B3]/20">
                        <div class="text-3xl mb-4">⚖️</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">選択の指針</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            AとBどちらを選ぶべきか迷った時、それぞれの選択がもたらす結果やアドバイスを示します。
                        </p>
                    </div>
                    <!-- 5. 対策・アドバイス -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#F687B3]/20">
                        <div class="text-3xl mb-4">💡</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">対策・アドバイス</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            困難を乗り越えるためにどう行動すべきか、具体的なアクションや心の持ち方を提案します。
                        </p>
                    </div>
                    <!-- 6. 潜在意識 -->
                    <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#F687B3]/20">
                        <div class="text-3xl mb-4">🌊</div>
                        <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">潜在意識</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                            自分でも気づいていない恐れや願望、ブロックになっている思い込みを浮き彫りにします。
                        </p>
                    </div>
                </div>
            </section>

            <!-- スリーカード解説 -->
            <section class="bg-[#FFFDF9] dark:bg-[#2A2E47]/10 rounded-2xl p-8 border border-[#F687B3]/30">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3">
                    <span class="text-3xl">🔗</span> 3枚で流れを読む「スリーカード」
                </h2>
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1 prose dark:prose-invert">
                        <p>
                            タロットに慣れていない方でも扱いやすいのが、3枚だけを引く「スリーカードスプレッド」です。
                            過去・現在・未来という時間軸で状況を切り分けることで、今のテーマを直感的に理解できます。
                        </p>
                        <p>
                            カードを引いた瞬間に感じたことや、並びから受け取る物語を大切にすることで、
                            「焦りの原因」「本当はどうしたいか」「次の一歩」といった気づきが自然と浮かび上がります。
                        </p>
                    </div>
                    <div class="flex-1 w-full max-w-md">
                        <div class="flex items-center justify-around bg-white dark:bg-[#2A2E47] rounded-2xl border border-[#F687B3]/30 shadow-inner p-6">
                            @foreach(['過去','現在','未来'] as $index => $label)
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-16 h-28 rounded-lg border border-[#F687B3] bg-[#F687B3]/15 flex items-center justify-center text-[#F687B3] font-semibold text-sm">
                                        {{ $label }}
                                    </div>
                                    <p class="text-xs text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                                        {{ ['ここまで','いま','これから'][$index] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <!-- サンプル診断結果 -->
            <section>
                <div class="text-center mb-8">
                    <span class="text-sm font-semibold text-[#F687B3] tracking-wider">SAMPLE RESULT</span>
                    <h2 class="text-2xl font-bold mt-2 text-[#2A2E47] dark:text-[#FFFDF9]">診断結果のイメージ</h2>
                    <p class="text-sm text-[#2A2E47]/60 dark:text-[#FFFDF9]/60 mt-2">
                        スリーカードスプレッドでの展開例
                    </p>
                </div>

                <div class="bg-white dark:bg-[#2A2E47]/30 rounded-2xl shadow-lg border border-[#F687B3]/20 overflow-hidden">
                    <div class="bg-[#F687B3]/10 p-6 text-center border-b border-[#F687B3]/20">
                        <h3 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9]">過去・現在・未来</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mt-1">
                            3枚のカードが示す、気持ちの移ろいとこれからのヒント
                        </p>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="grid lg:grid-cols-2 gap-10">
                            <div class="flex flex-col gap-6">
                                <div class="flex flex-col sm:flex-row justify-center gap-6">
                                    @foreach($sampleResult as $index => $card)
                                        <div class="flex flex-col items-center text-center bg-white/80 dark:bg-[#2A2E47]/60 rounded-2xl border border-[#F687B3]/30 p-4 shadow-sm">
                                            <div class="text-xs font-semibold text-[#F687B3] tracking-wide mb-3">
                                                {{ $positions[$index]['name'] }}
                                            </div>
                                            <div class="w-24 h-40 bg-white dark:bg-[#1C1F2E] border border-[#F687B3]/40 rounded-xl flex items-center justify-center overflow-hidden mb-3">
                                                <img src="{{ $card['card_image'] }}" alt="{{ $card['card_name'] }}" class="w-full h-full object-contain {{ $card['position'] === '逆位置' ? 'rotate-180' : '' }}">
                                            </div>
                                            <p class="text-sm font-semibold text-[#2A2E47] dark:text-[#FFFDF9]">{{ $card['card_name'] }} ({{ $card['position'] }})</p>
                                            <p class="text-xs text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mt-2">
                                                {{ \Illuminate\Support\Str::limit($card['message'], 70) }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-center text-[#2A2E47]/60 dark:text-[#FFFDF9]/60">
                                    ※ カードにカーソルを合わせると詳細メッセージを確認できます
                                </p>
                            </div>
                            <div class="bg-[#FFFDF9] dark:bg-[#2A2E47]/10 p-6 border border-[#F687B3]/20 rounded-2xl">
                                <h4 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-4">
                                    スリーカードの読み解き方
                                </h4>
                                <ol class="space-y-4 text-sm">
                                    @foreach($positions as $index => $pos)
                                        <li class="flex items-start gap-3">
                                            <span class="text-[#F687B3] font-bold">{{ $index + 1 }}.</span>
                                            <div>
                                                <p class="font-semibold text-[#2A2E47] dark:text-[#FFFDF9]">{{ $pos['name'] }}</p>
                                                <p class="text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">{{ $pos['desc'] }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                                <p class="text-xs text-[#2A2E47]/60 dark:text-[#FFFDF9]/60 mt-6">
                                    カードが語るストーリーは、あなたの感覚で自由に繋いでOK。<br>
                                    「過去」からの学びを受け取り、「現在」の気持ちを整理しながら、「未来」に向けた行動を選んでみましょう。
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </section>

            <!-- CTA -->
            <div class="text-center bg-gradient-to-r from-[#F687B3]/10 to-[#FBB6CE]/10 rounded-3xl p-10 border border-[#F687B3]/20">
                <h2 class="text-3xl font-bold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]">
                    タロットカードに尋ねてみませんか？
                </h2>
                <p class="text-lg text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 mb-8">
                    無料登録で、タロットカードがいつでもあなたの相談相手になります。<br>
                    迷ったとき、いつでもカードを引いてみてください。
                </p>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#F687B3] to-[#FBB6CE] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
                        ダッシュボードへ移動
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#F687B3] to-[#FBB6CE] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
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