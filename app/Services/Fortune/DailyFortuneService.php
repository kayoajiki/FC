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
}

