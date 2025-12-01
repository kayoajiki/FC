<?php

use App\Models\FortuneSummary;
use App\Models\Mood;
use App\Services\Fortune\DailyFortuneService;
use App\Services\Fortune\TarotService;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public ?array $dailyFortune = null;
    public ?Mood $todayMood = null;
    public ?FortuneSummary $myFortune = null;
    public ?array $tarotCard = null;
    public ?array $monthlyTheme = null;
    public ?array $todayBiorhythm = null;
    public array $weeklyBiorhythm = [];

    public int $moodRating = 3;
    public ?string $moodEmoji = null;
    public ?string $moodMemo = null;

    public bool $showGuide = false;

    /**
     * Get the layout for the component.
     */
    public function layout(): string
    {
        return 'layouts.app';
    }

    /**
     * Get the data to pass to the layout.
     */
    public function with(): array
    {
        return [
            'title' => 'ダッシュボード',
        ];
    }

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        
        // 今日の運勢を取得
        $dailyFortuneService = app(DailyFortuneService::class);
        $this->dailyFortune = $dailyFortuneService->calculateToday($user);
        
        // 今日の感情ログを取得
        $this->todayMood = Mood::where('user_id', $user->id)
            ->where('date', today())
            ->first();
        
        if ($this->todayMood) {
            $this->moodRating = $this->todayMood->mood_rating;
            $this->moodEmoji = $this->todayMood->mood_emoji;
            $this->moodMemo = $this->todayMood->memo;
        }
        
        // My命式を取得
        $this->myFortune = FortuneSummary::where('user_id', $user->id)
            ->latest('calculated_at')
            ->first();
        
        // 月次テーマを取得
        $this->monthlyTheme = $dailyFortuneService->getMonthlyTheme($user);
        
        // 今日のバイオリズムを取得
        $this->todayBiorhythm = $dailyFortuneService->calculateBiorhythm($user);
        
        // 今週のバイオリズム推移を取得
        $this->weeklyBiorhythm = $dailyFortuneService->getWeeklyBiorhythm($user);
    }

    /**
     * 感情ログを保存
     */
    public function saveMood(): void
    {
        $user = Auth::user();
        
        $validated = $this->validate([
            'moodRating' => ['required', 'integer', 'min:1', 'max:5'],
            'moodEmoji' => ['nullable', 'string', 'max:10'],
            'moodMemo' => ['nullable', 'string', 'max:500'],
        ]);

        Mood::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => today(),
            ],
            [
                'mood_rating' => $validated['moodRating'],
                'mood_emoji' => $validated['moodEmoji'],
                'memo' => $validated['moodMemo'],
            ]
        );

        $this->mount(); // データを再取得
        $this->dispatch('mood-saved');
    }

    /**
     * タロットカードを引く
     */
    public function drawTarot(): void
    {
        $tarotService = app(TarotService::class);
        $this->tarotCard = $tarotService->drawOne();
    }

    /**
     * ガイドを表示/非表示
     */
    public function toggleGuide(): void
    {
        $this->showGuide = !$this->showGuide;
    }

    /**
     * 診断を再計算する
     */
    public function recalculateFortune(): void
    {
        $user = Auth::user();
        
        if (!$user->birth_date) {
            session()->flash('error', '生年月日が登録されていません。プロフィール設定から生年月日を登録してください。');
            return;
        }
        
        $dailyFortuneService = app(DailyFortuneService::class);
        $this->dailyFortune = $dailyFortuneService->calculateToday($user);
        
        // FortuneSummaryを更新
        FortuneSummary::updateOrCreate(
            [
                'user_id' => $user->id,
                'calculated_at' => today(),
            ],
            [
                'birth_date' => $user->birth_date,
                'birth_time' => $user->birth_time,
                'birth_place' => $user->birth_place,
                'four_pillars_result' => $this->dailyFortune['four_pillars'] ?? null,
                'numerology_result' => $this->dailyFortune['numerology'] ?? null,
                'ziwei_result' => $this->dailyFortune['ziwei'] ?? null,
            ]
        );
        
        $this->myFortune = FortuneSummary::where('user_id', $user->id)
            ->latest('calculated_at')
            ->first();
        
        session()->flash('success', '診断を再計算しました。');
    }

    /**
     * 週の振り返りデータを取得
     */
    public function getWeeklyReportProperty()
    {
        $user = Auth::user();
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
        
        $moods = Mood::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
        
        if ($moods->isEmpty()) {
            return null;
        }
        
        $avgRating = $moods->avg('mood_rating');
        $mostCommonEmoji = $moods->whereNotNull('mood_emoji')
            ->groupBy('mood_emoji')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();
        
        return [
            'period' => $startDate->format('m/d') . ' - ' . $endDate->format('m/d'),
            'total_days' => $moods->count(),
            'avg_rating' => round($avgRating, 1),
            'most_common_emoji' => $mostCommonEmoji,
            'moods' => $moods,
        ];
    }

    /**
     * 月の振り返りデータを取得
     */
    public function getMonthlyReportProperty()
    {
        $user = Auth::user();
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $moods = Mood::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
        
        if ($moods->isEmpty()) {
            return null;
        }
        
        $avgRating = $moods->avg('mood_rating');
        $mostCommonEmoji = $moods->whereNotNull('mood_emoji')
            ->groupBy('mood_emoji')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();
        
        // 週ごとの平均を計算
        $weeklyAverages = $moods->groupBy(function ($mood) {
            return $mood->date->format('W');
        })->map(function ($weekMoods) {
            return round($weekMoods->avg('mood_rating'), 1);
        });
        
        return [
            'period' => $startDate->format('Y年m月'),
            'total_days' => $moods->count(),
            'avg_rating' => round($avgRating, 1),
            'most_common_emoji' => $mostCommonEmoji,
            'weekly_averages' => $weeklyAverages,
            'moods' => $moods,
        ];
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl" style="background-color: #FFFDF9;">
        <!-- 今日の運勢（Daily Light）カード -->
        @if($dailyFortune)
            <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F8A38A]/30 dark:border-[#E985A6]/30 p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <h2 class="text-2xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                            今日の運勢（Daily Light）
                        </h2>
                        <flux:button 
                            wire:click="recalculateFortune" 
                            variant="ghost" 
                            size="sm"
                            class="text-xs"
                            style="color: #F8A38A; font-family: 'Noto Sans JP', sans-serif;"
                        >
                            🔄 再計算
                        </flux:button>
                    </div>
                    <span class="text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                        {{ now()->format('Y年m月d日') }}
                    </span>
                </div>
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="grid md:grid-cols-3 gap-6">
                    <!-- テーマ -->
                    <div class="text-center p-4 rounded-lg bg-[#F8A38A]/10 dark:bg-[#E985A6]/10">
                        <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">今日のテーマ</div>
                        <div class="text-lg font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            {{ $dailyFortune['theme'] }}
                        </div>
                    </div>
                    
                    <!-- 心の向き -->
                    <div class="text-center p-4 rounded-lg bg-[#E985A6]/10 dark:bg-[#F8A38A]/10">
                        <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">心の向き</div>
                        <div class="text-lg font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            {{ $dailyFortune['direction'] }}
                        </div>
                    </div>
                    
                    <!-- 小さな一歩 -->
                    <div class="text-center p-4 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                        <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">小さな一歩</div>
                        <div class="text-lg font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            {{ $dailyFortune['small_step'] }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- タロットスプレッドカード -->
        <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F9C97D]/30 dark:border-[#F9C97D]/30 p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                    タロットスプレッド
                </h2>
                <flux:button wire:click="drawTarot" variant="primary" style="background-color: #F9C97D; color: #2A2E47; font-family: 'Noto Sans JP', sans-serif;">
                    カードを引く
                </flux:button>
            </div>
            
            @if($tarotCard)
                <div class="p-6 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                    <div class="flex flex-col md:flex-row gap-6 items-center md:items-start">
                        <!-- カード画像 -->
                        @if(!empty($tarotCard['card_image']))
                            <div class="flex-shrink-0">
                                <img 
                                    src="{{ $tarotCard['card_image'] }}" 
                                    alt="{{ $tarotCard['card_name'] ?? '' }}"
                                    class="w-48 h-72 object-contain rounded-lg shadow-lg {{ $tarotCard['position'] === '逆位置' ? 'rotate-180' : '' }}"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                />
                                <div class="w-48 h-72 bg-[#F9C97D]/20 rounded-lg shadow-lg flex items-center justify-center hidden" style="display: none;">
                                    <span class="text-4xl">🃏</span>
                                </div>
                            </div>
                        @else
                            <div class="flex-shrink-0 w-48 h-72 bg-[#F9C97D]/20 rounded-lg shadow-lg flex items-center justify-center">
                                <span class="text-4xl">🃏</span>
                            </div>
                        @endif
                        
                        <!-- カード情報 -->
                        <div class="flex-1 text-center md:text-left">
                            <div class="text-2xl mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                                {{ $tarotCard['card_name'] ?? '' }}
                            </div>
                            <div class="text-sm mb-4" style="color: #F9C97D; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                                {{ $tarotCard['position'] ?? '' }}
                            </div>
                            <p class="text-base leading-relaxed" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                {{ $tarotCard['message'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center text-gray-500" style="font-family: 'Noto Sans JP', sans-serif;">
                    「カードを引く」ボタンを押して、今この瞬間に必要なメッセージを受け取りましょう
                </p>
            @endif
        </div>

        <!-- 月次テーマカード -->
        @if($monthlyTheme)
            <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F9C97D]/30 dark:border-[#F9C97D]/30 p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                        今月のテーマ
                    </h2>
                    <span class="text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                        {{ now()->format('Y年m月') }}
                    </span>
                </div>
                <div class="space-y-4">
                    <div class="text-center p-6 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                        <div class="text-2xl mb-3" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                            🌅 {{ $monthlyTheme['theme'] }}
                        </div>
                        <p class="text-base leading-relaxed" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                            {{ $monthlyTheme['description'] }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- バイオリズムカード -->
        @if($todayBiorhythm)
            <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#E985A6]/30 dark:border-[#F8A38A]/30 p-6 shadow-lg">
                <h2 class="text-2xl font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                    バイオリズム
                </h2>
                
                <!-- 今日の値 -->
                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 rounded-lg bg-[#F8A38A]/10 dark:bg-[#E985A6]/10">
                        <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">身体的</div>
                        <div class="text-2xl font-semibold mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            {{ number_format($todayBiorhythm['physical'], 1) }}%
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#F8A38A] h-2 rounded-full" style="width: {{ $todayBiorhythm['physical'] }}%"></div>
                        </div>
                    </div>
                    
                    <div class="p-4 rounded-lg bg-[#E985A6]/10 dark:bg-[#F8A38A]/10">
                        <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">感情的</div>
                        <div class="text-2xl font-semibold mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            {{ number_format($todayBiorhythm['emotional'], 1) }}%
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#E985A6] h-2 rounded-full" style="width: {{ $todayBiorhythm['emotional'] }}%"></div>
                        </div>
                    </div>
                    
                    <div class="p-4 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                        <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">知的</div>
                        <div class="text-2xl font-semibold mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            {{ number_format($todayBiorhythm['intellectual'], 1) }}%
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#F9C97D] h-2 rounded-full" style="width: {{ $todayBiorhythm['intellectual'] }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- 今週の推移グラフ -->
                @if(count($weeklyBiorhythm) > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            今週の推移
                        </h3>
                        <div class="relative h-64 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                            <canvas id="biorhythmChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- 感情ログ（Mood Record）カード -->
        <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F8A38A]/30 dark:border-[#E985A6]/30 p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                    感情ログ（Mood Record）
                </h2>
                @if($todayMood)
                    <span class="text-sm px-3 py-1 rounded-full bg-[#F8A38A]/20 text-[#2A2E47]" style="font-family: 'Noto Sans JP', sans-serif;">
                        ✓ 今日記録済み
                    </span>
                @endif
            </div>
            
            <form wire:submit="saveMood" class="space-y-4">
                <!-- ハート5段階 -->
                <div>
                    <label class="block text-sm mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                        今日の気持ち（1〜5）
                    </label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                wire:click="$set('moodRating', {{ $i }})"
                                class="text-3xl transition-transform hover:scale-110 {{ $moodRating >= $i ? 'text-[#F8A38A]' : 'text-gray-300' }}"
                            >
                                ♥
                            </button>
                        @endfor
                    </div>
                </div>
                
                <!-- 気分絵文字 -->
                <div>
                    <label class="block text-sm mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                        気分絵文字（任意）
                    </label>
                    <flux:input
                        wire:model="moodEmoji"
                        type="text"
                        placeholder="😊"
                        maxlength="10"
                    />
                </div>
                
                <!-- メモ -->
                <div>
                    <label class="block text-sm mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                        メモ（任意）
                    </label>
                    <flux:textarea
                        wire:model="moodMemo"
                        placeholder="今日の気持ちや出来事を記録しましょう"
                        rows="3"
                        maxlength="500"
                    />
                </div>
                
                <flux:button type="submit" variant="primary" class="w-full" style="background-color: #2A2E47; color: #FFFDF9; font-family: 'Noto Sans JP', sans-serif;">
                    保存する
                </flux:button>
            </form>
        </div>

        <!-- 占い師に相談（チャット） -->
        <div class="mt-8">
            <h2 class="text-2xl font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                占い師に相談
            </h2>
            <livewire:chat.index />
        </div>

        <!-- 振り返りレポート -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- 週の振り返り -->
            @if($this->weeklyReport)
                <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#E985A6]/30 dark:border-[#F8A38A]/30 p-6 shadow-lg">
                    <h2 class="text-xl font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                        週の振り返り
                    </h2>
                    <div class="space-y-4">
                        <div class="text-center p-4 rounded-lg bg-[#E985A6]/10 dark:bg-[#F8A38A]/10">
                            <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                                {{ $this->weeklyReport['period'] }}
                            </div>
                            <div class="text-2xl font-semibold mb-1" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                                {{ $this->weeklyReport['avg_rating'] }}/5.0
                            </div>
                            <div class="text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                                {{ $this->weeklyReport['total_days'] }}日記録
                            </div>
                        </div>
                        @if($this->weeklyReport['most_common_emoji'])
                            <div class="text-center">
                                <div class="text-3xl mb-2">{{ $this->weeklyReport['most_common_emoji'] }}</div>
                                <div class="text-xs" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                                    よく使った絵文字
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- 月の振り返り -->
            @if($this->monthlyReport)
                <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F9C97D]/30 dark:border-[#F9C97D]/30 p-6 shadow-lg">
                    <h2 class="text-xl font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                        月の振り返り
                    </h2>
                    <div class="space-y-4">
                        <div class="text-center p-4 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                            <div class="text-sm mb-2" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                                {{ $this->monthlyReport['period'] }}
                            </div>
                            <div class="text-2xl font-semibold mb-1" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                                {{ $this->monthlyReport['avg_rating'] }}/5.0
                            </div>
                            <div class="text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                                {{ $this->monthlyReport['total_days'] }}日記録
                            </div>
                        </div>
                        @if($this->monthlyReport['most_common_emoji'])
                            <div class="text-center">
                                <div class="text-3xl mb-2">{{ $this->monthlyReport['most_common_emoji'] }}</div>
                                <div class="text-xs" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                                    よく使った絵文字
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- My命式（詳細版）カード -->
        @if($myFortune)
            <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F8A38A]/30 dark:border-[#E985A6]/30 p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                        My命式（詳細版）
                    </h2>
                    <flux:button 
                        wire:click="recalculateFortune" 
                        variant="ghost" 
                        size="sm"
                        class="text-xs"
                        style="color: #F8A38A; font-family: 'Noto Sans JP', sans-serif;"
                    >
                        🔄 再計算
                    </flux:button>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- 四柱推命 -->
                    @if($myFortune->four_pillars_result)
                        <div class="p-4 rounded-lg bg-[#F8A38A]/10 dark:bg-[#E985A6]/10">
                            <h3 class="text-lg font-semibold mb-3" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">四柱推命</h3>
                            @if(isset($myFortune->four_pillars_result['formula']))
                                <p class="text-sm mb-3" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                    {{ $myFortune->four_pillars_result['formula'] }}
                                </p>
                            @endif
                            @if(isset($myFortune->four_pillars_result['strengths']))
                                <div class="space-y-2">
                                    <h4 class="text-sm font-medium" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">あなたの強み</h4>
                                    <ul class="text-sm space-y-1" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                        @foreach($myFortune->four_pillars_result['strengths'] as $strength)
                                            <li>• {{ $strength }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <!-- 数秘術 -->
                    @if($myFortune->numerology_result)
                        <div class="p-4 rounded-lg bg-[#E985A6]/10 dark:bg-[#F8A38A]/10">
                            <h3 class="text-lg font-semibold mb-3" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">数秘術</h3>
                            @if(isset($myFortune->numerology_result['life_path_number']))
                                <p class="text-sm mb-3" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                    ライフパスナンバー: <span class="text-[#F9C97D] font-semibold">{{ $myFortune->numerology_result['life_path_number'] }}</span>
                                </p>
                            @endif
                            @if(isset($myFortune->numerology_result['strengths']))
                                <div class="space-y-2">
                                    <h4 class="text-sm font-medium" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">あなたの強み</h4>
                                    <ul class="text-sm space-y-1" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                        @foreach($myFortune->numerology_result['strengths'] as $strength)
                                            <li>• {{ $strength }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <!-- 紫微斗数 -->
                    @if($myFortune->ziwei_result)
                        <div class="p-4 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                            <h3 class="text-lg font-semibold mb-3" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">紫微斗数</h3>
                            @if(isset($myFortune->ziwei_result['strengths']))
                                <div class="space-y-2">
                                    <h4 class="text-sm font-medium" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">あなたの強み</h4>
                                    <ul class="text-sm space-y-1" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                        @foreach($myFortune->ziwei_result['strengths'] as $strength)
                                            <li>• {{ $strength }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F8A38A]/30 dark:border-[#E985A6]/30 p-6 shadow-lg text-center">
                <p class="text-lg mb-4" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                    My命式がまだありません
                </p>
                <flux:button href="{{ route('home') }}" variant="primary" style="background-color: #2A2E47; color: #FFFDF9; font-family: 'Noto Sans JP', sans-serif;">
                    無料診断を受ける
                </flux:button>
            </div>
        @endif

        <!-- ガイドコンテンツ -->
        <div class="bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl border border-[#F8A38A]/30 dark:border-[#E985A6]/30 p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 700;">
                    使い方ガイド
                </h2>
                <flux:button wire:click="toggleGuide" variant="ghost" style="color: #F8A38A; font-family: 'Noto Sans JP', sans-serif;">
                    {{ $showGuide ? '閉じる' : '開く' }}
                </flux:button>
            </div>
            
            @if($showGuide)
                <div class="space-y-6" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                    <!-- Fortune Compassとは -->
                    <div class="p-4 rounded-lg bg-[#F8A38A]/10 dark:bg-[#E985A6]/10">
                        <h3 class="text-lg font-semibold mb-3" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            Fortune Compassとは
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                            Fortune Compassは、四柱推命・紫微斗数・数秘術・タロットの4つの占術を通じて、
                            あなた自身を深く知り、毎日の羅針盤として活用できるサービスです。
                            迷ったとき、自分に戻れる光を届けることを目的としています。
                        </p>
                    </div>
                    
                    <!-- 日常への活かし方 -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            日常への活かし方
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- 今日の運勢をどう読むか -->
                            <div class="p-4 rounded-lg bg-[#E985A6]/10 dark:bg-[#F8A38A]/10">
                                <h4 class="font-semibold mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                                    今日の運勢をどう読むか
                                </h4>
                                <p class="text-sm leading-relaxed" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                    今日のテーマ・心の向き・小さな一歩を参考に、一日を過ごしてみてください。
                                    占いの結果を「決めつけ」ではなく、「選択肢の一つ」として受け取ることが大切です。
                                </p>
                            </div>
                            
                            <!-- 感情ログで自分を振り返る方法 -->
                            <div class="p-4 rounded-lg bg-[#E985A6]/10 dark:bg-[#F8A38A]/10">
                                <h4 class="font-semibold mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                                    感情ログで自分を振り返る方法
                                </h4>
                                <p class="text-sm leading-relaxed" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                    毎日、自分の気持ちを5段階のハートと絵文字、メモで記録しましょう。
                                    時々過去の記録を見返すことで、自分の感情のパターンに気づくことができます。
                                </p>
                            </div>
                            
                            <!-- My命式を日常の判断に活かす -->
                            <div class="p-4 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                                <h4 class="font-semibold mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                                    My命式を日常の判断に活かす
                                </h4>
                                <p class="text-sm leading-relaxed" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                    自分の強みや注意点を知ることで、日常の判断をより良いものにできます。
                                    占いの結果は「決めつけ」ではなく、「自分を知るためのツール」として活用しましょう。
                                </p>
                            </div>
                            
                            <!-- タロットスプレッドの読み方 -->
                            <div class="p-4 rounded-lg bg-[#F9C97D]/10 dark:bg-[#F9C97D]/10">
                                <h4 class="font-semibold mb-2" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                                    タロットスプレッドの読み方
                                </h4>
                                <p class="text-sm leading-relaxed" style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">
                                    タロットカードは、今この瞬間に必要なメッセージを伝えてくれます。
                                    正位置・逆位置に関わらず、カードからのメッセージを素直に受け取り、
                                    自分自身の直感と照らし合わせて解釈することが大切です。
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- よくある質問（FAQ） -->
                    <div class="p-4 rounded-lg bg-[#F8A38A]/10 dark:bg-[#E985A6]/10">
                        <h3 class="text-lg font-semibold mb-3" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                            よくある質問（FAQ）
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <h4 class="font-semibold mb-1" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">Q: 出生時刻が不明でも使えますか？</h4>
                                <p style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">A: はい、使えます。ただし、紫微斗数のみ出生時刻が必要なため、時刻が不明な場合は表示されません。</p>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">Q: 結果は毎日変わるのですか？</h4>
                                <p style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">A: 今日の運勢は毎日変わりますが、My命式は基本的に同じです（生年月日が変わることはないため）。</p>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">Q: タロットカードは何回でも引けますか？</h4>
                                <p style="color: rgba(42, 46, 71, 0.8); font-family: 'Noto Sans JP', sans-serif;">A: はい、何回でも引くことができます。ただし、同じ質問に対して何度も引くことは避けることをお勧めします。</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
</div>

@if(count($weeklyBiorhythm) > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            const ctx = document.getElementById('biorhythmChart');
            if (!ctx) return;

            const weeklyData = @json($weeklyBiorhythm);
            
            const labels = weeklyData.map(item => item.day_label);
            const physicalData = weeklyData.map(item => item.physical);
            const emotionalData = weeklyData.map(item => item.emotional);
            const intellectualData = weeklyData.map(item => item.intellectual);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '身体的',
                            data: physicalData,
                            borderColor: '#F8A38A',
                            backgroundColor: 'rgba(248, 163, 138, 0.1)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: '感情的',
                            data: emotionalData,
                            borderColor: '#E985A6',
                            backgroundColor: 'rgba(233, 133, 166, 0.1)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: '知的',
                            data: intellectualData,
                            borderColor: '#F9C97D',
                            backgroundColor: 'rgba(249, 201, 125, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    family: "'Noto Sans JP', sans-serif",
                                },
                                color: '#2A2E47'
                            }
                        },
                        tooltip: {
                            font: {
                                family: "'Noto Sans JP', sans-serif",
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                font: {
                                    family: "'Noto Sans JP', sans-serif",
                                },
                                color: '#2A2E47',
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: 'rgba(42, 46, 71, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: "'Noto Sans JP', sans-serif",
                                },
                                color: '#2A2E47'
                            },
                            grid: {
                                color: 'rgba(42, 46, 71, 0.1)'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endif

