<?php

namespace App\Services\Fortune;

use Carbon\Carbon;

/**
 * 四柱推命計算サービス
 */
class FourPillarsService
{
    /**
     * 四柱推命の計算を実行
     *
     * @param Carbon $birthDate 生年月日
     * @param string|null $birthTime 出生時刻（HH:MM形式または"不明"）
     * @param string|null $birthPlace 出生地（都道府県）
     * @return array 四柱推命の結果
     */
    public function calculate(Carbon $birthDate, ?string $birthTime = null, ?string $birthPlace = null): array
    {
        // 日干の算出
        $dayStem = $this->calculateDayStem($birthDate);
        
        // 五行バランスの計算
        $fiveElements = $this->calculateFiveElements($birthDate, $dayStem);
        
        // 十干十二支の組み合わせ
        $stemsAndBranches = $this->calculateStemsAndBranches($birthDate);
        
        // 強み3つ・注意1つの判定
        $strengths = $this->determineStrengths($dayStem, $fiveElements);
        $caution = $this->determineCaution($dayStem, $fiveElements);

        return [
            'day_stem' => $dayStem,
            'five_elements' => $fiveElements,
            'stems_and_branches' => $stemsAndBranches,
            'strengths' => $strengths,
            'caution' => $caution,
            'formula' => $this->generateFormula($stemsAndBranches),
        ];
    }

    /**
     * 日干を算出
     */
    private function calculateDayStem(Carbon $birthDate): string
    {
        // 簡易的な日干計算（実際の計算ロジックはより複雑）
        $dayOfYear = $birthDate->dayOfYear;
        $stems = ['甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸'];
        return $stems[($dayOfYear - 1) % 10];
    }

    /**
     * 五行バランスを計算
     */
    private function calculateFiveElements(Carbon $birthDate, string $dayStem): array
    {
        // 五行マッピング
        $elementMap = [
            '甲' => '木', '乙' => '木',
            '丙' => '火', '丁' => '火',
            '戊' => '土', '己' => '土',
            '庚' => '金', '辛' => '金',
            '壬' => '水', '癸' => '水',
        ];

        $mainElement = $elementMap[$dayStem] ?? '木';
        
        // 簡易的な五行バランス計算
        return [
            '木' => $mainElement === '木' ? 3 : rand(1, 2),
            '火' => $mainElement === '火' ? 3 : rand(1, 2),
            '土' => $mainElement === '土' ? 3 : rand(1, 2),
            '金' => $mainElement === '金' ? 3 : rand(1, 2),
            '水' => $mainElement === '水' ? 3 : rand(1, 2),
        ];
    }

    /**
     * 十干十二支の組み合わせを計算
     */
    private function calculateStemsAndBranches(Carbon $birthDate): array
    {
        $stems = ['甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸'];
        $branches = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];
        
        $year = $birthDate->year;
        $month = $birthDate->month;
        $day = $birthDate->day;
        
        // 簡易的な計算（実際はより複雑なロジックが必要）
        return [
            'year' => [
                'stem' => $stems[($year - 4) % 10],
                'branch' => $branches[($year - 4) % 12],
            ],
            'month' => [
                'stem' => $stems[($month - 1) % 10],
                'branch' => $branches[($month - 1) % 12],
            ],
            'day' => [
                'stem' => $stems[($day - 1) % 10],
                'branch' => $branches[($day - 1) % 12],
            ],
            'hour' => [
                'stem' => null, // 時刻が必要
                'branch' => null,
            ],
        ];
    }

    /**
     * 強み3つを判定
     */
    private function determineStrengths(string $dayStem, array $fiveElements): array
    {
        // 簡易的な強み判定ロジック
        $strengths = [
            '直感力が優れている',
            'コミュニケーション能力が高い',
            '創造性に富んでいる',
        ];
        
        return array_slice($strengths, 0, 3);
    }

    /**
     * 注意点1つを判定
     */
    private function determineCaution(string $dayStem, array $fiveElements): string
    {
        // 簡易的な注意点判定ロジック
        return '感情の起伏に注意が必要です';
    }

    /**
     * 命式を生成
     */
    private function generateFormula(array $stemsAndBranches): string
    {
        $year = $stemsAndBranches['year']['stem'] . $stemsAndBranches['year']['branch'];
        $month = $stemsAndBranches['month']['stem'] . $stemsAndBranches['month']['branch'];
        $day = $stemsAndBranches['day']['stem'] . $stemsAndBranches['day']['branch'];
        
        return "{$year}年 {$month}月 {$day}日";
    }
}

