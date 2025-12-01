<?php

namespace App\Services\Fortune;

use App\Models\User;
use Carbon\Carbon;

/**
 * 今日の運勢サービス
 */
class DailyFortuneService
{
    public function __construct(
        private FourPillarsService $fourPillarsService,
        private NumerologyService $numerologyService,
        private ZiweiService $ziweiService,
    ) {
    }

    /**
     * 今日の運勢を計算
     *
     * @param User|null $user ユーザー（nullの場合は生年月日を直接指定）
     * @param Carbon|null $birthDate 生年月日（ユーザーがnullの場合に使用）
     * @param string|null $birthTime 出生時刻
     * @return array 今日の運勢
     */
    public function calculateToday(?User $user = null, ?Carbon $birthDate = null, ?string $birthTime = null): array
    {
        if ($user) {
            $birthDate = $user->birth_date ? Carbon::parse($user->birth_date) : null;
            $birthTime = $user->birth_time;
        }

        if (!$birthDate) {
            return $this->getDefaultFortune();
        }

        // 四柱推命・数秘術・紫微斗数の結果を取得
        $fourPillars = $this->fourPillarsService->calculate($birthDate, $birthTime);
        $numerology = $this->numerologyService->calculate($birthDate);
        $ziwei = $birthTime && $birthTime !== '不明' 
            ? $this->ziweiService->calculate($birthDate, $birthTime) 
            : null;

        // 今日の運勢スコア（0-100）を算出
        $score = $this->calculateScore($fourPillars, $numerology, $ziwei);

        // 今日のテーマ・心の向き・小さな一歩を生成
        $theme = $this->generateTheme($fourPillars, $numerology, $ziwei);
        $direction = $this->generateDirection($fourPillars, $numerology, $ziwei);
        $smallStep = $this->generateSmallStep($fourPillars, $numerology, $ziwei);

        return [
            'date' => Carbon::today()->format('Y-m-d'),
            'score' => $score,
            'theme' => $theme,
            'direction' => $direction,
            'small_step' => $smallStep,
            'four_pillars' => $fourPillars,
            'numerology' => $numerology,
            'ziwei' => $ziwei,
        ];
    }

    /**
     * 運勢スコアを計算（0-100）
     */
    private function calculateScore(array $fourPillars, array $numerology, ?array $ziwei): int
    {
        // 簡易的なスコア計算（実際はより複雑なロジック）
        $baseScore = 50;
        
        // 五行バランスから調整
        $fiveElements = $fourPillars['five_elements'] ?? [];
        $balance = array_sum($fiveElements) / count($fiveElements);
        $baseScore += (int) (($balance - 2) * 10);
        
        // 数秘術から調整
        $lifePath = $numerology['life_path_number'] ?? 5;
        $baseScore += ($lifePath - 5) * 2;
        
        // 紫微斗数から調整（あれば）
        if ($ziwei) {
            $baseScore += 5;
        }
        
        // 0-100の範囲に収める
        return max(0, min(100, $baseScore));
    }

    /**
     * 今日のテーマを生成
     */
    private function generateTheme(array $fourPillars, array $numerology, ?array $ziwei): string
    {
        $themes = [
            '新しい始まりの日',
            '内面を見つめる日',
            '行動を起こす日',
            '調和を大切にする日',
            '創造性を発揮する日',
        ];
        
        $dayStem = $fourPillars['day_stem'] ?? '甲';
        $stems = ['甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸'];
        $index = array_search($dayStem, $stems) !== false ? array_search($dayStem, $stems) : 0;
        return $themes[$index % count($themes)];
    }

    /**
     * 心の向きを生成
     */
    private function generateDirection(array $fourPillars, array $numerology, ?array $ziwei): string
    {
        $directions = [
            '前向きに進む気持ちを大切に',
            '周囲との調和を意識して',
            '自分の直感を信じて',
            '冷静に判断して',
            '感謝の気持ちを持って',
        ];
        
        $index = ($numerology['life_path_number'] ?? 5) % count($directions);
        return $directions[$index];
    }

    /**
     * 小さな一歩を生成
     */
    private function generateSmallStep(array $fourPillars, array $numerology, ?array $ziwei): string
    {
        $steps = [
            '今日は新しいことにチャレンジしてみましょう',
            '身近な人に感謝の言葉を伝えましょう',
            '少しだけ自分の時間を作りましょう',
            '深呼吸をしてリラックスしましょう',
            '小さな目標を一つ達成しましょう',
        ];
        
        $index = rand(0, count($steps) - 1);
        return $steps[$index];
    }

    /**
     * デフォルトの運勢（生年月日が不明な場合）
     */
    private function getDefaultFortune(): array
    {
        return [
            'date' => Carbon::today()->format('Y-m-d'),
            'score' => 50,
            'theme' => '今日という日を大切に',
            'direction' => '今この瞬間を大切に',
            'small_step' => '一歩ずつ進んでいきましょう',
        ];
    }

    /**
     * 月次テーマを取得（個人差を強くする）
     *
     * @param User|null $user ユーザー
     * @param Carbon|null $birthDate 生年月日
     * @return array 月次テーマ情報
     */
    public function getMonthlyTheme(?User $user = null, ?Carbon $birthDate = null): array
    {
        if ($user) {
            $birthDate = $user->birth_date ? Carbon::parse($user->birth_date) : null;
        }

        if (!$birthDate) {
            return [
                'theme' => '今月という月を大切に',
                'description' => '今月も一歩ずつ進んでいきましょう',
            ];
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 生年月日の全要素を使用して個人差を強くする
        $birthYear = $birthDate->year;
        $birthMonth = $birthDate->month;
        $birthDay = $birthDate->day;

        // 個人のシード値を計算（年・月・日を組み合わせ）
        $seed = ($birthYear * 10000 + $birthMonth * 100 + $birthDay) % 1000;
        
        // 現在の月と組み合わせてテーマを決定
        $themeIndex = ($seed + $currentMonth + $currentYear) % 12;

        $themes = [
            [
                'theme' => '新しい始まりの月',
                'description' => '今月は新しいことにチャレンジするのに適した時期です。変化を恐れず、一歩を踏み出してみましょう。',
            ],
            [
                'theme' => '内面を見つめる月',
                'description' => '今月は自分自身と向き合い、内面を深く見つめる時期です。静かな時間を作り、心の声に耳を傾けましょう。',
            ],
            [
                'theme' => '行動を起こす月',
                'description' => '今月は考えていることを行動に移すのに適した時期です。迷わず、直感を信じて動き出しましょう。',
            ],
            [
                'theme' => '調和を大切にする月',
                'description' => '今月は周囲との調和を意識することが大切です。相手の気持ちに寄り添い、協調性を大切にしましょう。',
            ],
            [
                'theme' => '創造性を発揮する月',
                'description' => '今月は創造性が高まる時期です。新しいアイデアを形にしたり、表現活動に取り組むのに適しています。',
            ],
            [
                'theme' => '学びと成長の月',
                'description' => '今月は学びと成長に焦点を当てる時期です。新しい知識を吸収し、スキルアップを目指しましょう。',
            ],
            [
                'theme' => '感謝と振り返りの月',
                'description' => '今月はこれまでの歩みを振り返り、感謝の気持ちを大切にする時期です。周囲への感謝を伝えましょう。',
            ],
            [
                'theme' => '決断と選択の月',
                'description' => '今月は重要な決断を下す時期です。迷いがある場合は、自分の直感を信じて選択しましょう。',
            ],
            [
                'theme' => '関係性を深める月',
                'description' => '今月は人間関係を深めるのに適した時期です。大切な人との時間を大切にし、絆を深めましょう。',
            ],
            [
                'theme' => '休息と回復の月',
                'description' => '今月は無理をせず、休息と回復を優先する時期です。心身を整え、次のステップに備えましょう。',
            ],
            [
                'theme' => '目標達成の月',
                'description' => '今月は目標に向かって着実に進む時期です。小さなステップを積み重ね、目標に近づきましょう。',
            ],
            [
                'theme' => '変化と転機の月',
                'description' => '今月は変化と転機が訪れる可能性がある時期です。変化を受け入れ、新しい可能性に目を向けましょう。',
            ],
        ];

        return $themes[$themeIndex];
    }

    /**
     * バイオリズムを計算
     *
     * @param User|null $user ユーザー
     * @param Carbon|null $birthDate 生年月日
     * @param Carbon|null $targetDate 対象日（nullの場合は今日）
     * @return array バイオリズムの値（0-100%）
     */
    public function calculateBiorhythm(?User $user = null, ?Carbon $birthDate = null, ?Carbon $targetDate = null): array
    {
        if ($user) {
            $birthDate = $user->birth_date ? Carbon::parse($user->birth_date) : null;
        }

        if (!$birthDate) {
            return [
                'physical' => 50,
                'emotional' => 50,
                'intellectual' => 50,
            ];
        }

        $targetDate = $targetDate ?? Carbon::today();
        $daysSinceBirth = $birthDate->diffInDays($targetDate);

        // バイオリズムの計算（sin波を使用）
        // 身体的: 23日周期
        $physical = sin(2 * M_PI * $daysSinceBirth / 23) * 50 + 50;
        
        // 感情的: 28日周期
        $emotional = sin(2 * M_PI * $daysSinceBirth / 28) * 50 + 50;
        
        // 知的: 33日周期
        $intellectual = sin(2 * M_PI * $daysSinceBirth / 33) * 50 + 50;

        return [
            'physical' => round($physical, 1),
            'emotional' => round($emotional, 1),
            'intellectual' => round($intellectual, 1),
            'date' => $targetDate->format('Y-m-d'),
        ];
    }

    /**
     * 今週のバイオリズム推移を取得
     *
     * @param User|null $user ユーザー
     * @param Carbon|null $birthDate 生年月日
     * @return array 今週のバイオリズムデータ
     */
    public function getWeeklyBiorhythm(?User $user = null, ?Carbon $birthDate = null): array
    {
        if ($user) {
            $birthDate = $user->birth_date ? Carbon::parse($user->birth_date) : null;
        }

        if (!$birthDate) {
            return [];
        }

        $weeklyData = [];
        $startDate = Carbon::today()->subDays(6); // 7日間（今日を含む）

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $biorhythm = $this->calculateBiorhythm($user, $birthDate, $date);
            
            $weeklyData[] = [
                'date' => $date->format('Y-m-d'),
                'date_label' => $date->format('m/d'),
                'day_label' => $date->format('D') === 'Sun' ? '日' : 
                              ($date->format('D') === 'Mon' ? '月' : 
                              ($date->format('D') === 'Tue' ? '火' : 
                              ($date->format('D') === 'Wed' ? '水' : 
                              ($date->format('D') === 'Thu' ? '木' : 
                              ($date->format('D') === 'Fri' ? '金' : '土'))))),
                'physical' => $biorhythm['physical'],
                'emotional' => $biorhythm['emotional'],
                'intellectual' => $biorhythm['intellectual'],
            ];
        }

        return $weeklyData;
    }
}

