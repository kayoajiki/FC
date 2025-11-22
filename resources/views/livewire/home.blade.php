<?php

use App\Services\Fortune\FourPillarsService;
use App\Services\Fortune\NumerologyService;
use App\Services\Fortune\TarotService;
use App\Services\Fortune\ZiweiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;

new class extends Component {
    public ?string $birth_date = null;
    public ?string $birth_time = null;
    public ?string $birth_hour = null;
    public ?string $birth_minute = null;
    public ?string $birth_place = null;
    
    public ?array $fourPillarsResult = null;
    public ?array $numerologyResult = null;
    public ?array $ziweiResult = null;
    public ?array $tarotResult = null;
    
    public bool $showResults = false;

    /**
     * Get Japanese prefectures list.
     */
    public function getPrefectures(): array
    {
        return [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
        ];
    }

    /**
     * Update birth_time when hour or minute changes
     */
    public function updateBirthTime(): void
    {
        if ($this->birth_hour && $this->birth_minute) {
            $this->birth_time = sprintf('%02d:%02d', $this->birth_hour, $this->birth_minute);
        } elseif ($this->birth_time !== '不明') {
            $this->birth_time = null;
        }
    }

    /**
     * Update when birth_time radio button changes
     */
    public function updatedBirthTime(): void
    {
        if ($this->birth_time === '不明') {
            $this->birth_hour = null;
            $this->birth_minute = null;
        }
    }

    /**
     * Mount the component
     */
    public function mount(): void
    {
        // 既存のbirth_timeからhourとminuteを分解
        if ($this->birth_time && $this->birth_time !== '不明') {
            $parts = explode(':', $this->birth_time);
            if (count($parts) === 2) {
                $this->birth_hour = $parts[0];
                $this->birth_minute = $parts[1];
            }
        }
    }

    /**
     * Calculate fortune results
     */
    public function calculateFortune(): void
    {
        $prefectures = $this->getPrefectures();
        
        $validated = $this->validate([
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:today', // 未来の日付は不可
                'after_or_equal:1900-01-01', // 1900年以降のみ
            ],
            'birth_time' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '不明' && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
                        $fail('出生時刻は「不明」または「HH:MM」形式で入力してください。');
                    }
                },
            ],
            'birth_place' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($prefectures) {
                    if ($value !== null && !in_array($value, $prefectures)) {
                        $fail('出生地は都道府県を選択してください。');
                    }
                },
            ],
        ], [
            'birth_date.required' => '生年月日を入力してください。',
            'birth_date.date' => '有効な日付を入力してください。',
            'birth_date.before_or_equal' => '未来の日付は入力できません。',
            'birth_date.after_or_equal' => '1900年以降の日付を入力してください。',
        ]);

        try {
            $birthDate = Carbon::parse($validated['birth_date']);
            
            // 未来の日付チェック（念のため）
            if ($birthDate->isFuture()) {
                throw new \InvalidArgumentException('未来の日付は入力できません。');
            }
            
            // 1900年以前の日付チェック
            if ($birthDate->year < 1900) {
                throw new \InvalidArgumentException('1900年以降の日付を入力してください。');
            }
            
            $birthTime = $validated['birth_time'] ?: null;
            $birthPlace = $validated['birth_place'] ?: null;

            // 四柱推命計算
            $fourPillarsService = app(FourPillarsService::class);
            $this->fourPillarsResult = $fourPillarsService->calculate($birthDate, $birthTime, $birthPlace);

            // 数秘術計算
            $numerologyService = app(NumerologyService::class);
            $this->numerologyResult = $numerologyService->calculate($birthDate);

            // 紫微斗数計算（出生時刻が必要）
            if ($birthTime && $birthTime !== '不明') {
                $ziweiService = app(ZiweiService::class);
                $this->ziweiResult = $ziweiService->calculate($birthDate, $birthTime, $birthPlace);
            }

            // タロット1枚引き
            $tarotService = app(TarotService::class);
            $this->tarotResult = $tarotService->drawOne();

            // 結果をセッションに保存（ログイン時に紐づけるため）
            Session::put('fortune_calculation', [
                'birth_date' => $birthDate->format('Y-m-d'),
                'birth_time' => $birthTime,
                'birth_place' => $birthPlace,
                'four_pillars' => $this->fourPillarsResult,
                'numerology' => $this->numerologyResult,
                'ziwei' => $this->ziweiResult,
                'tarot' => $this->tarotResult,
            ]);

            $this->showResults = true;
            
            // 結果表示位置までスクロール
            $this->dispatch('scroll-to-results');
        } catch (\InvalidArgumentException $e) {
            // バリデーションエラー
            $this->addError('birth_date', $e->getMessage());
        } catch (\Exception $e) {
            // その他のエラー
            \Log::error('Fortune calculation error: ' . $e->getMessage(), [
                'birth_date' => $this->birth_date,
                'birth_time' => $this->birth_time,
                'birth_place' => $this->birth_place,
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', '診断の計算中にエラーが発生しました。もう一度お試しください。');
        }
    }
}; ?>

<div>
    <!-- Header -->
    @php
        $headerImage = asset('images/header-background.jpg');
        $hasHeaderImage = file_exists(public_path('images/header-background.jpg'));
    @endphp
    <header class="relative w-full mb-8 overflow-hidden" style="@if($hasHeaderImage) background-image: url('{{ $headerImage }}'); background-size: cover; background-position: center; @else background-color: #FFFDF9; @endif">
        <div class="absolute inset-0 bg-[#FFFDF9]/80 dark:bg-[#2A2E47]/80"></div>
        <div class="relative w-full max-w-7xl mx-auto px-4 py-6">
            <nav class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-xl font-semibold text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                    Fortune Compass
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm text-[#2A2E47] dark:text-[#FFFDF9] hover:text-[#F8A38A] transition-colors">ダッシュボード</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-[#2A2E47] dark:text-[#FFFDF9] hover:text-[#F8A38A] transition-colors">ログイン</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-[#2A2E47] dark:bg-[#FFFDF9] text-[#FFFDF9] dark:text-[#2A2E47] rounded-lg hover:bg-[#F8A38A] dark:hover:bg-[#F9C97D] transition-colors">
                                無料登録
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </div>
    </header>
    <!-- Hero Section -->
    <section class="py-12 lg:py-20 text-center" style="background-color: #FFFDF9;">
        <h1 class="text-4xl lg:text-5xl font-semibold mb-4 text-[#2A2E47] dark:text-[#FFFDF9] max-w-4xl mx-auto" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
            迷ったとき、自分に戻れる光を届ける
        </h1>
        <p class="text-lg text-[#2A2E47]/80 dark:text-[#FFFDF9]/80 max-w-2xl mx-auto mb-8" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 400;">
            Fortune Compassは、四柱推命・紫微斗数・数秘術・タロットの4つの占術を通じて、
            あなた自身を深く知り、毎日の羅針盤として活用できるサービスです。
        </p>
    </section>

    <!-- 4占術の簡易説明 -->
    <section class="max-w-6xl mx-auto px-4 mb-12" style="background-color: #FFFDF9;">
        <h2 class="text-3xl font-semibold mb-8 text-center text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">4つの占術について</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-[#2A2E47]/10 rounded-lg shadow-lg p-6 border border-[#F8A38A]/20 dark:border-[#E985A6]/20 hover:border-[#F8A38A] dark:hover:border-[#E985A6] transition-colors">
                <h3 class="text-xl font-semibold mb-3 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">四柱推命</h3>
                <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-4" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 400;">
                    生年月日と時刻から、あなたの本質的な性質や運勢の流れを読み解きます。
                </p>
                <a href="{{ route('four-pillars') }}" class="text-sm text-[#F8A38A] dark:text-[#E985A6] hover:text-[#E985A6] dark:hover:text-[#F9C97D] transition-colors inline-flex items-center gap-1">
                    詳しく見る →
                </a>
            </div>
            <div class="bg-white dark:bg-[#2A2E47]/10 rounded-lg shadow-lg p-6 border border-[#F8A38A]/20 dark:border-[#E985A6]/20 hover:border-[#F8A38A] dark:hover:border-[#E985A6] transition-colors">
                <h3 class="text-xl font-semibold mb-3 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">紫微斗数</h3>
                <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-4" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 400;">
                    出生時刻が必要な、中国の高精度な占星術。詳細な運勢の流れを読み解きます。
                </p>
                <a href="{{ route('ziwei') }}" class="text-sm text-[#F8A38A] dark:text-[#E985A6] hover:text-[#E985A6] dark:hover:text-[#F9C97D] transition-colors inline-flex items-center gap-1">
                    詳しく見る →
                </a>
            </div>
            <div class="bg-white dark:bg-[#2A2E47]/10 rounded-lg shadow-lg p-6 border border-[#F8A38A]/20 dark:border-[#E985A6]/20 hover:border-[#F8A38A] dark:hover:border-[#E985A6] transition-colors">
                <h3 class="text-xl font-semibold mb-3 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">数秘術</h3>
                <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-4" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 400;">
                    生年月日から導き出すライフパスナンバーで、あなたの人生のテーマを理解します。
                </p>
                <a href="{{ route('numerology') }}" class="text-sm text-[#F8A38A] dark:text-[#E985A6] hover:text-[#E985A6] dark:hover:text-[#F9C97D] transition-colors inline-flex items-center gap-1">
                    詳しく見る →
                </a>
            </div>
            <div class="bg-white dark:bg-[#2A2E47]/10 rounded-lg shadow-lg p-6 border border-[#F8A38A]/20 dark:border-[#E985A6]/20 hover:border-[#F8A38A] dark:hover:border-[#E985A6] transition-colors">
                <h3 class="text-xl font-semibold mb-3 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">タロット</h3>
                <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-4" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 400;">
                    78枚のカードから、今この瞬間に必要なメッセージを受け取ります。
                </p>
                <a href="{{ route('tarot') }}" class="text-sm text-[#F8A38A] dark:text-[#E985A6] hover:text-[#E985A6] dark:hover:text-[#F9C97D] transition-colors inline-flex items-center gap-1">
                    詳しく見る →
                </a>
            </div>
        </div>
    </section>

    <!-- Birth Information Form -->
    <section class="max-w-2xl mx-auto px-4 mb-12" style="background-color: #FFFDF9;">
        <div class="bg-white/80 dark:bg-[#2A2E47]/80 backdrop-blur-sm rounded-lg shadow-lg p-8 border border-[#F8A38A]/30 dark:border-[#E985A6]/30">
            <h2 class="text-2xl font-semibold mb-6 text-center text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">無料診断を始める</h2>
            
            <!-- エラーメッセージ表示 -->
            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <p class="text-sm text-red-800 dark:text-red-200" style="font-family: 'Noto Sans JP', sans-serif;">
                        {{ session('error') }}
                    </p>
                </div>
            @endif
            
            <form wire:submit="calculateFortune" class="space-y-6">
                <div>
                    <flux:field>
                        <flux:label>生年月日</flux:label>
                        <flux:input wire:model="birth_date" type="date" required autocomplete="bday" />
                        <flux:error name="birth_date" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>出生時刻</flux:label>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">不明</label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" wire:model="birth_time" value="不明" class="w-4 h-4 text-[#F8A38A] border-gray-300 focus:ring-[#F8A38A]">
                                    <span class="text-sm" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif;">出生時刻が不明</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">時刻を指定する</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs mb-1" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">時</label>
                                        <select wire:model="birth_hour" wire:change="updateBirthTime" data-flux-control class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-gray-600 dark:bg-gray-800">
                                            <option value="">--</option>
                                            @for($hour = 0; $hour < 24; $hour++)
                                                <option value="{{ sprintf('%02d', $hour) }}">{{ sprintf('%02d', $hour) }}時</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs mb-1" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">分</label>
                                        <select wire:model="birth_minute" wire:change="updateBirthTime" data-flux-control class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-gray-600 dark:bg-gray-800">
                                            <option value="">--</option>
                                            @for($minute = 0; $minute < 60; $minute++)
                                                <option value="{{ sprintf('%02d', $minute) }}">{{ sprintf('%02d', $minute) }}分</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <flux:error name="birth_time" />
                        <flux:description>出生時刻が不明な場合、紫微斗数の診断は表示されません。</flux:description>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>出生地（都道府県）</flux:label>
                        <select wire:model="birth_place" data-flux-control class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-gray-600 dark:bg-gray-800">
                            <option value="">{{ __('Select...') }}</option>
                            @foreach($this->getPrefectures() as $prefecture)
                                <option value="{{ $prefecture }}">{{ $prefecture }}</option>
                            @endforeach
                        </select>
                        <flux:error name="birth_place" />
                    </flux:field>
                </div>

                <flux:button type="submit" variant="primary" class="w-full">
                    今すぐ無料診断
                </flux:button>
            </form>
        </div>
    </section>

    <!-- Results Section -->
    @if($showResults)
        <section id="results" class="max-w-6xl mx-auto px-4 mb-12" style="background-color: #FFFDF9;">
            <h2 class="text-3xl font-semibold mb-8 text-center text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">診断結果</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- 四柱推命 -->
                @if($fourPillarsResult)
                    <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-lg shadow-lg p-6 border border-[#F8A38A]/30 dark:border-[#E985A6]/30 hover:border-[#F8A38A] dark:hover:border-[#E985A6] transition-colors">
                        <h3 class="text-xl font-semibold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">四柱推命</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-4" style="font-family: 'Noto Sans JP', sans-serif;">{{ $fourPillarsResult['formula'] ?? '' }}</p>
                        <div class="space-y-3">
                            <div>
                                <h4 class="font-medium mb-2 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">あなたの強み</h4>
                                <ul class="text-sm space-y-1 text-[#2A2E47]/80 dark:text-[#FFFDF9]/80" style="font-family: 'Noto Sans JP', sans-serif;">
                                    @foreach($fourPillarsResult['strengths'] ?? [] as $strength)
                                        <li>• {{ $strength }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">注意点</h4>
                                <p class="text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80" style="font-family: 'Noto Sans JP', sans-serif;">{{ $fourPillarsResult['caution'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 数秘術 -->
                @if($numerologyResult)
                    <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-lg shadow-lg p-6 border border-[#F8A38A]/30 dark:border-[#E985A6]/30 hover:border-[#F8A38A] dark:hover:border-[#E985A6] transition-colors">
                        <h3 class="text-xl font-semibold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">数秘術</h3>
                        <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-4" style="font-family: 'Noto Sans JP', sans-serif;">ライフパスナンバー: <span class="text-[#F9C97D] font-semibold">{{ $numerologyResult['life_path_number'] ?? '' }}</span></p>
                        <div class="space-y-3">
                            <div>
                                <h4 class="font-medium mb-2 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">あなたの強み</h4>
                                <ul class="text-sm space-y-1 text-[#2A2E47]/80 dark:text-[#FFFDF9]/80" style="font-family: 'Noto Sans JP', sans-serif;">
                                    @foreach($numerologyResult['strengths'] ?? [] as $strength)
                                        <li>• {{ $strength }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">注意点</h4>
                                <p class="text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80" style="font-family: 'Noto Sans JP', sans-serif;">{{ $numerologyResult['caution'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 紫微斗数 -->
                @if($ziweiResult)
                    <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-lg shadow-lg p-6 border border-[#F8A38A]/30 dark:border-[#E985A6]/30 hover:border-[#F8A38A] dark:hover:border-[#E985A6] transition-colors">
                        <h3 class="text-xl font-semibold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">紫微斗数</h3>
                        <div class="space-y-3">
                            <div>
                                <h4 class="font-medium mb-2 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">あなたの強み</h4>
                                <ul class="text-sm space-y-1 text-[#2A2E47]/80 dark:text-[#FFFDF9]/80" style="font-family: 'Noto Sans JP', sans-serif;">
                                    @foreach($ziweiResult['strengths'] ?? [] as $strength)
                                        <li>• {{ $strength }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">注意点</h4>
                                <p class="text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80" style="font-family: 'Noto Sans JP', sans-serif;">{{ $ziweiResult['caution'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @elseif($birth_time === '不明' || !$birth_time)
                    <div class="bg-white/50 dark:bg-[#2A2E47]/50 backdrop-blur-sm rounded-lg shadow-lg p-6 border border-[#F8A38A]/20 dark:border-[#E985A6]/20 opacity-60">
                        <h3 class="text-xl font-semibold mb-4 text-[#2A2E47]/50 dark:text-[#FFFDF9]/50" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">紫微斗数</h3>
                        <p class="text-sm text-[#2A2E47]/50 dark:text-[#FFFDF9]/50" style="font-family: 'Noto Sans JP', sans-serif;">出生時刻が必要です</p>
                    </div>
                @endif

                <!-- タロット -->
                @if($tarotResult)
                    <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-lg shadow-lg p-6 border border-[#F9C97D]/30 dark:border-[#F9C97D]/30 hover:border-[#F9C97D] dark:hover:border-[#F9C97D] transition-colors">
                        <h3 class="text-xl font-semibold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">タロット</h3>
                        <div class="space-y-3">
                            <div class="text-center">
                                <p class="font-medium text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">{{ $tarotResult['card_name'] ?? '' }}</p>
                                <p class="text-sm text-[#F9C97D] dark:text-[#F9C97D]" style="font-family: 'Noto Sans JP', sans-serif;">{{ $tarotResult['position'] ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#2A2E47]/80 dark:text-[#FFFDF9]/80" style="font-family: 'Noto Sans JP', sans-serif;">{{ $tarotResult['message'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- CTA Section -->
            <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-lg shadow-lg p-8 border border-[#F8A38A]/30 dark:border-[#E985A6]/30 text-center">
                <h3 class="text-2xl font-semibold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">もっと詳しく知りたい方へ</h3>
                <p class="text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-6" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 400;">
                    無料登録で、今日の運勢・感情ログ・My命式の詳細版・タロットスプレッドを利用できます。
                </p>
                @auth
                    <flux:button href="{{ route('dashboard') }}" variant="primary" class="bg-[#2A2E47] hover:bg-[#F8A38A] text-[#FFFDF9]">
                        ダッシュボードへ
                    </flux:button>
                @else
                    <div class="flex gap-4 justify-center">
                        <flux:button href="{{ route('register') }}" variant="primary" class="bg-[#2A2E47] hover:bg-[#F8A38A] text-[#FFFDF9]">
                            無料登録する
                        </flux:button>
                        <flux:button href="{{ route('login') }}" variant="ghost" class="border-[#F8A38A] text-[#2A2E47] dark:text-[#FFFDF9] hover:bg-[#F8A38A]/10">
                            ログイン
                        </flux:button>
                    </div>
                @endauth
            </div>

            <!-- 関連記事への誘導 -->
            <div class="mt-8 text-center">
                <h3 class="text-xl font-semibold mb-4 text-[#2A2E47] dark:text-[#FFFDF9]" style="font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">関連記事</h3>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('four-pillars') }}" class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 hover:text-[#F8A38A] dark:hover:text-[#E985A6] transition-colors" style="font-family: 'Noto Sans JP', sans-serif;">
                        四柱推命で見る自分の強みを活かす方法
                    </a>
                    <span class="text-[#2A2E47]/50 dark:text-[#FFFDF9]/50">•</span>
                    <a href="{{ route('numerology') }}" class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 hover:text-[#F8A38A] dark:hover:text-[#E985A6] transition-colors" style="font-family: 'Noto Sans JP', sans-serif;">
                        数秘術から見る人生のテーマ
                    </a>
                    <span class="text-[#2A2E47]/50 dark:text-[#FFFDF9]/50">•</span>
                    <a href="{{ route('tarot') }}" class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 hover:text-[#F8A38A] dark:hover:text-[#E985A6] transition-colors" style="font-family: 'Noto Sans JP', sans-serif;">
                        タロットの読み方と活かし方
                    </a>
                </div>
            </div>
        </section>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scroll-to-results', () => {
                setTimeout(() => {
                    document.getElementById('results')?.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            });
        });
    </script>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto px-4 py-12 mt-20 border-t border-[#F8A38A]/20 dark:border-[#E985A6]/20" style="background-color: #FFFDF9;">
        <div class="text-center text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70" style="font-family: 'Noto Sans JP', sans-serif;">
            <p>&copy; {{ date('Y') }} Fortune Compass. All rights reserved.</p>
        </div>
    </footer>
</div>

