<?php

namespace App\Services\Fortune;

use Carbon\Carbon;

/**
 * 紫微斗数計算サービス
 * 注意: 出生時刻が必要
 */
class ZiweiService
{
    /**
     * 紫微斗数の計算を実行
     *
     * @param Carbon $birthDate 生年月日
     * @param string|null $birthTime 出生時刻（HH:MM形式、必須）
     * @param string|null $birthPlace 出生地（都道府県）
     * @return array|null 紫微斗数の結果（出生時刻が不明の場合はnull）
     */
    public function calculate(Carbon $birthDate, ?string $birthTime = null, ?string $birthPlace = null): ?array
    {
        // 出生時刻が不明の場合は計算不可
        if ($birthTime === null || $birthTime === '不明') {
            return null;
        }

        // 命盤の星配置の計算
        $starPlacement = $this->calculateStarPlacement($birthDate, $birthTime);
        
        // 宮位の計算
        $palaces = $this->calculatePalaces($birthDate, $birthTime);
        
        // 強み3つ・注意1つの判定
        $strengths = $this->determineStrengths($starPlacement, $palaces);
        $caution = $this->determineCaution($starPlacement, $palaces);

        return [
            'star_placement' => $starPlacement,
            'palaces' => $palaces,
            'strengths' => $strengths,
            'caution' => $caution,
            'life_palace' => $this->getLifePalace($palaces),
        ];
    }

    /**
     * 命盤の星配置を計算
     */
    private function calculateStarPlacement(Carbon $birthDate, string $birthTime): array
    {
        // 簡易的な星配置計算（実際の計算ロジックはより複雑）
        $hour = (int) explode(':', $birthTime)[0];
        $stars = ['紫微', '天機', '太陽', '武曲', '天同', '廉貞', '天府', '太陰', '貪狼', '巨門', '天相', '天梁', '七殺', '破軍'];
        
        return [
            'main_stars' => array_slice($stars, 0, 5),
            'secondary_stars' => array_slice($stars, 5, 5),
            'hour_star' => $stars[$hour % count($stars)],
        ];
    }

    /**
     * 宮位を計算
     */
    private function calculatePalaces(Carbon $birthDate, string $birthTime): array
    {
        $hour = (int) explode(':', $birthTime)[0];
        $palaces = ['命宮', '兄弟宮', '夫妻宮', '子女宮', '財帛宮', '疾厄宮', '遷移宮', '奴僕宮', '官禄宮', '田宅宮', '福德宮', '父母宮'];
        
        // 簡易的な宮位計算
        $lifePalaceIndex = $hour % 12;
        
        $result = [];
        for ($i = 0; $i < 12; $i++) {
            $result[] = [
                'name' => $palaces[($lifePalaceIndex + $i) % 12],
                'index' => $i,
            ];
        }
        
        return $result;
    }

    /**
     * 強み3つを判定
     */
    private function determineStrengths(array $starPlacement, array $palaces): array
    {
        // 簡易的な強み判定ロジック
        return [
            '直感力と洞察力に優れている',
            '人間関係を築く能力が高い',
            '目標達成への意志が強い',
        ];
    }

    /**
     * 注意点1つを判定
     */
    private function determineCaution(array $starPlacement, array $palaces): string
    {
        // 簡易的な注意点判定ロジック
        return '感情のコントロールに注意が必要です';
    }

    /**
     * 命宮を取得
     */
    private function getLifePalace(array $palaces): array
    {
        foreach ($palaces as $palace) {
            if ($palace['name'] === '命宮') {
                return $palace;
            }
        }
        
        return $palaces[0];
    }
}

