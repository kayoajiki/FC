<?php

namespace App\Services\Fortune;

use Carbon\Carbon;

/**
 * 数秘術計算サービス
 */
class NumerologyService
{
    /**
     * 数秘術の計算を実行
     *
     * @param Carbon $birthDate 生年月日
     * @return array 数秘術の結果
     */
    public function calculate(Carbon $birthDate): array
    {
        // ライフパスナンバーの計算
        $lifePathNumber = $this->calculateLifePathNumber($birthDate);
        
        // 強み3つ・注意1つの判定
        $strengths = $this->determineStrengths($lifePathNumber);
        $caution = $this->determineCaution($lifePathNumber);

        return [
            'life_path_number' => $lifePathNumber,
            'strengths' => $strengths,
            'caution' => $caution,
            'characteristics' => $this->getCharacteristics($lifePathNumber),
        ];
    }

    /**
     * ライフパスナンバーを計算
     */
    private function calculateLifePathNumber(Carbon $birthDate): int
    {
        $year = $this->reduceToSingleDigit($birthDate->year);
        $month = $this->reduceToSingleDigit($birthDate->month);
        $day = $this->reduceToSingleDigit($birthDate->day);
        
        $sum = $year + $month + $day;
        
        // マスターナンバー（11, 22, 33）を考慮
        if (in_array($sum, [11, 22, 33])) {
            return $sum;
        }
        
        return $this->reduceToSingleDigit($sum);
    }

    /**
     * 数字を1桁に還元（マスターナンバーはそのまま）
     */
    private function reduceToSingleDigit(int $number): int
    {
        while ($number > 9 && !in_array($number, [11, 22, 33])) {
            $number = array_sum(str_split((string) $number));
        }
        
        return $number;
    }

    /**
     * 強み3つを判定
     */
    private function determineStrengths(int $lifePathNumber): array
    {
        $strengthsMap = [
            1 => ['リーダーシップ', '独立性', '創造性'],
            2 => ['協調性', '感受性', 'バランス感覚'],
            3 => ['表現力', 'コミュニケーション', '楽観性'],
            4 => ['実務能力', '安定性', '信頼性'],
            5 => ['自由さ', '冒険心', '適応力'],
            6 => ['責任感', '愛情深さ', '調和'],
            7 => ['分析力', '直感力', '探究心'],
            8 => ['実力', '組織力', '成功志向'],
            9 => ['理想主義', '共感性', '奉仕精神'],
            11 => ['直感力', 'インスピレーション', '理想主義'],
            22 => ['実践力', '建設性', 'マスタービルダー'],
            33 => ['慈愛', '奉仕', 'マスターティーチャー'],
        ];

        return $strengthsMap[$lifePathNumber] ?? ['個性', '独自性', '可能性'];
    }

    /**
     * 注意点1つを判定
     */
    private function determineCaution(int $lifePathNumber): string
    {
        $cautionMap = [
            1 => '独りよがりにならないよう注意',
            2 => '優柔不断になりすぎないよう注意',
            3 => '散漫にならないよう注意',
            4 => '柔軟性を失わないよう注意',
            5 => '落ち着きを保つよう注意',
            6 => '過保護にならないよう注意',
            7 => '孤立しすぎないよう注意',
            8 => '物質主義に偏らないよう注意',
            9 => '理想と現実のバランスを保つよう注意',
            11 => '現実逃避に注意',
            22 => '完璧主義に注意',
            33 => '自己犠牲に注意',
        ];

        return $cautionMap[$lifePathNumber] ?? 'バランスを保つよう注意';
    }

    /**
     * ライフパスナンバーの特徴を取得
     */
    private function getCharacteristics(int $lifePathNumber): string
    {
        $characteristicsMap = [
            1 => 'リーダーシップと独立性を持つ開拓者',
            2 => '協調性と感受性を持つ平和主義者',
            3 => '表現力と創造性を持つ芸術家',
            4 => '実務能力と安定性を持つ建設者',
            5 => '自由と冒険を愛する探検家',
            6 => '責任感と愛情深さを持つケアギバー',
            7 => '分析力と直感力を持つ探究者',
            8 => '実力と組織力を持つ実業家',
            9 => '理想主義と共感性を持つ人道主義者',
            11 => '直感力とインスピレーションを持つマスターナンバー',
            22 => '実践力と建設性を持つマスタービルダー',
            33 => '慈愛と奉仕を持つマスターティーチャー',
        ];

        return $characteristicsMap[$lifePathNumber] ?? '独自の個性を持つ人';
    }
}

