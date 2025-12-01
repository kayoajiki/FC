<?php

use Livewire\Volt\Component;
use App\Services\Fortune\FourPillarsService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('components.layouts.guest')]
#[Title('四柱推命とは - Fortune Compass')]
class extends Component {
    public array $sampleResult = [];
    public string $pageDescription = '「占いの帝王」とも呼ばれる四柱推命。生年月日と時刻から、あなたの本質、運勢のバイオリズム、適性を驚くほど詳細に読み解きます。';

    public function mount(FourPillarsService $service): void
    {
        $birthDate = Carbon::create(1990, 1, 1, 12, 0, 0);
        $this->sampleResult = $service->calculate($birthDate, '12:00');
    }

    public function with(): array
    {
        return [
            'description' => $this->pageDescription,
            'ogType' => 'article',
            'ogTitle' => '四柱推命とは - Fortune Compass',
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
                'headline' => '四柱推命とは - 驚異の的中率を誇る「占いの帝王」',
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
            ['label' => '四柱推命とは']
        ]" />

        <header class="mb-12 text-center">
            <div class="inline-block px-3 py-1 mb-4 text-sm font-medium text-[#F8A38A] bg-[#F8A38A]/10 rounded-full">
                Four Pillars of Destiny
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif;">
                四柱推命とは
            </h1>
            <p class="text-xl text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 max-w-2xl mx-auto leading-relaxed">
                古代中国から伝わる「占いの帝王」。<br>
                単なる占いを超えた、人生の羅針盤となる統計学です。
            </p>
        </header>

        <div class="space-y-20">
            <section class="prose prose-lg dark:prose-invert max-w-none">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3 border-b-2 border-[#F8A38A]/30 pb-2">
                    <span class="text-3xl">👑</span> なぜ「占いの帝王」と呼ばれるのか？
                </h2>
                <p class="leading-loose">
                    四柱推命は、生まれた「年」「月」「日」「時」の4つの柱から運命を推し量る、中国発祥の占術です。その歴史は数千年に及び、膨大なデータの蓄積に基づく「統計学」としての側面も持ち合わせています。
                </p>
                <p class="leading-loose">
                    その的中率の高さから「占いの帝王」と呼ばれ、かつては政治や戦争の判断にも用いられました。現代においては、自分自身の強みや適性を知り、運気の波に乗るための最強のツールとして活用されています。
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold mb-8 text-[#2A2E47] dark:text-[#FFFDF9] text-center">
                    四柱推命でわかる6つのこと
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach([
                        ['icon' => '🌱', 'title' => '性格・本質', 'body' => '表に見える性格だけでなく、本人も気づいていない潜在的な本質や、魂が求めている生き方を読み解きます。'],
                        ['icon' => '🎨', 'title' => '適性・才能', 'body' => '生まれ持った才能や、それを活かせる環境、天職とも言える職業分野がわかります。'],
                        ['icon' => '🌊', 'title' => '運勢の流れ', 'body' => '10年ごとの大運、1年ごとの年運から、人生のバイオリズムや転機となる時期を予測します。'],
                        ['icon' => '💞', 'title' => '人間関係・相性', 'body' => 'パートナー、家族、ビジネスパートナーとの相性や、より良い関係を築くためのポイントがわかります。'],
                        ['icon' => '💼', 'title' => '職業・キャリア', 'body' => '組織で輝くタイプか、独立起業に向くタイプかなど、キャリア形成の指針となる情報が得られます。'],
                        ['icon' => '🏥', 'title' => '健康・体質', 'body' => '五行のバランスから、弱りやすい臓器や体質的な傾向を知り、日々の健康管理に活かせます。'],
                    ] as $item)
                        <div class="bg-white dark:bg-[#2A2E47]/20 p-6 rounded-xl shadow-sm border border-[#F8A38A]/20">
                            <div class="text-3xl mb-4">{{ $item['icon'] }}</div>
                            <h3 class="text-lg font-bold mb-2 text-[#2A2E47] dark:text-[#FFFDF9]">{{ $item['title'] }}</h3>
                            <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                                {{ $item['body'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="bg-[#FFFDF9] dark:bg-[#2A2E47]/10 rounded-2xl p-8 border border-[#F8A38A]/30">
                <h2 class="text-2xl font-bold mb-6 text-[#2A2E47] dark:text-[#FFFDF9] flex items-center gap-3">
                    <span class="text-3xl">☯️</span> 五行（ごぎょう）バランスとは？
                </h2>
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1 prose dark:prose-invert">
                        <p>
                            四柱推命の基礎となるのが「木・火・土・金・水」の5つの元素（五行）です。
                            宇宙の万物はすべてこの5つの要素から成り立っていると考えられています。
                        </p>
                        <p>
                            あなたの命式の中で、これらの要素がどのようなバランスで存在しているかを見ることで、
                            性格の傾向や健康状態、ラッキーカラーなどが導き出されます。
                            不足している要素を補うことで、運気を整えることができるのです。
                        </p>
                    </div>
                    <div class="flex-1 bg-white dark:bg-[#2A2E47] p-6 rounded-xl shadow-inner w-full max-w-md">
                        <div class="grid grid-cols-2 gap-4 text-center text-sm">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-800 dark:text-green-200">
                                <strong>木</strong><br>成長・発展
                            </div>
                            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg text-red-800 dark:text-red-200">
                                <strong>火</strong><br>情熱・明朗
                            </div>
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg text-yellow-800 dark:text-yellow-200 col-span-2 w-2/3 mx-auto">
                                <strong>土</strong><br>育成・受容
                            </div>
                            <div class="p-3 bg-gray-100 dark:bg-gray-700/30 rounded-lg text-gray-800 dark:text-gray-200">
                                <strong>金</strong><br>改革・収穫
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-800 dark:text-blue-200">
                                <strong>水</strong><br>知性・柔軟
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-[#FFFDF9] dark:bg-[#2A2E47]/10 rounded-2xl p-8 border border-[#F8A38A]/30 space-y-8">
                <div class="text-center">
                    <span class="text-sm font-semibold text-[#F8A38A] tracking-wider">MATRIX</span>
                    <h2 class="text-2xl font-bold mt-2 text-[#2A2E47] dark:text-[#FFFDF9]">命式のイメージ</h2>
                    <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mt-2">
                        四つの柱（年・月・日・時）に、あなたの根っこや運勢の流れが凝縮されています
                    </p>
                </div>

                <div class="prose prose-lg dark:prose-invert max-w-none">
                    <p>
                        命式（めいしき）は、生年月日と出生時刻をもとに作られる「あなた専用の宇宙地図」です。
                        年柱はルーツ、月柱は社会での顔、日柱は素の自分、時柱は未来の姿や子どもとの関わりを表し、それぞれの柱に天干・地支が並びます。
                    </p>
                    <p>
                        Fortune Compassでは、専門家が行う読み解きをそのままの順番でUI化。
                        どこを見ればいいのかが一目でわかるよう、テーマや読み方を隣に添えた構成にしています。
                    </p>
                </div>

                @php
                    $rawPillars = $sampleResult['stems_and_branches'] ?? [];
                    $pillarMeta = [
                        'year' => [
                            'label' => '年柱',
                            'role' => 'ルーツ・家族の流れ',
                            'stem_star' => '偏財',
                            'hidden_star' => '庚（偏印）',
                            'twelve_un' => '胎',
                        ],
                        'month' => [
                            'label' => '月柱',
                            'role' => '社会運・仕事の癖',
                            'stem_star' => '食神',
                            'hidden_star' => '庚（比肩）',
                            'twelve_un' => '建禄',
                        ],
                        'day' => [
                            'label' => '日柱',
                            'role' => '本質・恋愛観',
                            'stem_star' => '',
                            'hidden_star' => '丁（劫財）',
                            'twelve_un' => '建禄',
                        ],
                        'hour' => [
                            'label' => '時柱',
                            'role' => '晩年運・子どもとの関わり',
                            'stem_star' => '正官',
                            'hidden_star' => '丁（偏財）',
                            'twelve_un' => '冠帯',
                        ],
                    ];

                    $fallbackPillars = [
                        'year' => ['stem' => '己', 'branch' => '巳'],
                        'month' => ['stem' => '丙', 'branch' => '申'],
                        'day' => ['stem' => '庚', 'branch' => '午'],
                        'hour' => ['stem' => '壬', 'branch' => '午'],
                    ];

                    $pillars = [];
                    foreach ($pillarMeta as $key => $meta) {
                        $stem = data_get($rawPillars, "{$key}.stem");
                        $branch = data_get($rawPillars, "{$key}.branch");
                        $stem = $stem ?? data_get($fallbackPillars, "{$key}.stem", '—');
                        $branch = $branch ?? data_get($fallbackPillars, "{$key}.branch", '—');

                        $pillars[] = [
                            'label' => $meta['label'],
                            'stem' => $stem,
                            'branch' => $branch,
                            'role' => $meta['role'],
                            'stem_star' => $meta['stem_star'],
                            'hidden_star' => $meta['hidden_star'],
                            'twelve_un' => $meta['twelve_un'],
                        ];
                    }
                @endphp

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 border border-[#F8A38A]/30 rounded-xl overflow-hidden">
                        <thead class="bg-[#FFF5F0] dark:bg-[#2A2E47] text-[#2A2E47] dark:text-[#FFFDF9]">
                            <tr>
                                <th class="py-3 px-4">柱</th>
                                <th class="py-3 px-4 text-center">天干</th>
                                <th class="py-3 px-4 text-center">地支</th>
                                <th class="py-3 px-4">見るテーマ</th>
                                <th class="py-3 px-4 text-center">天干通変星</th>
                                <th class="py-3 px-4 text-center">蔵干通変星</th>
                                <th class="py-3 px-4 text-center">十二運</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F8A38A]/20">
                            @foreach($pillars as $pillar)
                                <tr class="bg-white/80 dark:bg-[#2A2E47]/40">
                                    <td class="py-3 px-4 font-semibold">{{ $pillar['label'] }}</td>
                                    <td class="py-3 px-4 text-lg font-bold text-[#F08E8E] text-center">{{ $pillar['stem'] }}</td>
                                    <td class="py-3 px-4 text-lg font-bold text-[#2A2E47] dark:text-[#FFFDF9] text-center">{{ $pillar['branch'] }}</td>
                                    <td class="py-3 px-4">{{ $pillar['role'] }}</td>
                                    <td class="py-3 px-4 text-center">{{ $pillar['stem_star'] }}</td>
                                    <td class="py-3 px-4 text-center">{{ $pillar['hidden_star'] }}</td>
                                    <td class="py-3 px-4 text-center">{{ $pillar['twelve_un'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="prose prose-sm dark:prose-invert max-w-none text-[#2A2E47]/80 dark:text-[#FFFDF9]/80">
                    <p>
                        表の見方はシンプルです。例えば月柱に注目すると「社会でどう振る舞うと力が発揮されるか」がわかり、
                        日柱は「あなたの芯＝素顔」を示します。五行バランスや十干の組み合わせを重ねて読むことで、強みと注意ポイントが立体的になります。
                    </p>
                    <p>
                        Fortune Compassでは、専門家が行う読み解きをガイド付きで提供。気になる箇所をタップすれば補足も表示され、
                        初心者でも自分の命式を自分の言葉で説明できる状態まで伴走します。
                    </p>
                </div>
            </section>

            <section>
                <div class="text-center mb-8">
                    <span class="text-sm font-semibold text-[#F8A38A] tracking-wider">SAMPLE RESULT</span>
                    <h2 class="text-2xl font-bold mt-2 text-[#2A2E47] dark:text-[#FFFDF9]">診断結果のイメージ</h2>
                    <p class="text-sm text-[#2A2E47]/60 dark:text-[#FFFDF9]/60 mt-2">
                        1990年1月1日 12:00生まれの場合
                    </p>
                </div>

                <div class="bg-white dark:bg-[#2A2E47]/30 rounded-2xl shadow-lg border border-[#F8A38A]/20 overflow-hidden">
                    <div class="bg-[#F8A38A]/10 p-6 text-center border-b border-[#F8A38A]/20">
                        <h3 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9]">あなたの命式</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mt-1">
                            {{ $sampleResult['formula'] }}
                        </p>
                    </div>

                    <div class="p-6 md:p-8 space-y-8">
                        <div class="text-center">
                            <p class="text-sm font-medium text-[#2A2E47]/60 dark:text-[#FFFDF9]/60 mb-2">あなたの本質を表す「日干」</p>
                            <div class="inline-block w-16 h-16 rounded-full bg-gradient-to-br from-[#F8A38A] to-[#E985A6] text-white text-3xl font-bold flex items-center justify-center mb-3 shadow-md mx-auto">
                                {{ $sampleResult['day_stem'] }}
                            </div>
                            <p class="text-[#2A2E47] dark:text-[#FFFDF9] font-medium max-w-lg mx-auto">
                                あなたは「{{ $sampleResult['day_stem'] }}」の性質を持っています。<br>
                                これは自然界で例えると...（詳細な解説が表示されます）
                            </p>
                        </div>

                        <div>
                            <h4 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-[#F8A38A] rounded-full"></span>
                                五行バランス
                            </h4>
                            <div class="space-y-3">
                                @foreach($sampleResult['five_elements'] as $element => $value)
                                    @php
                                        $percentage = min(100, ($value / 5) * 100);
                                        $colors = [
                                            '木' => 'bg-green-400',
                                            '火' => 'bg-red-400',
                                            '土' => 'bg-yellow-400',
                                            '金' => 'bg-gray-400',
                                            '水' => 'bg-blue-400',
                                        ];
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 text-sm font-bold text-[#2A2E47] dark:text-[#FFFDF9]">{{ $element }}</span>
                                        <div class="flex-1 h-3 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full {{ $colors[$element] }} transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-[#2A2E47]/60 dark:text-[#FFFDF9]/60 w-8 text-right">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-[#2A2E47]/50 dark:text-[#FFFDF9]/50 mt-3 text-right">
                                ※ 数値が高いほど、その要素の性質が強く現れます。
                            </p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800/30">
                                <h4 class="font-bold text-blue-800 dark:text-blue-300 mb-2 flex items-center gap-2">
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

            <div class="text-center bg-gradient-to-r from-[#F8A38A]/10 to-[#E985A6]/10 rounded-3xl p-10 border border-[#F8A38A]/20">
                <h2 class="text-3xl font-bold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]">
                    あなたの命式を見てみませんか？
                </h2>
                <p class="text-lg text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 mb-8">
                    Fortune Compassに登録すると、あなたの四柱推命の診断結果を<br class="hidden md:inline">
                    すべて無料で見ることができます。
                </p>

                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#F8A38A] to-[#E985A6] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
                        ダッシュボードへ移動
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-[#F8A38A] to-[#E985A6] rounded-full shadow-lg hover:shadow-xl hover:scale-105">
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