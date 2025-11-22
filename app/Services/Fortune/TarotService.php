<?php

namespace App\Services\Fortune;

/**
 * タロットサービス（78枚対応）
 */
class TarotService
{
    /**
     * 大アルカナ22枚
     */
    private const MAJOR_ARCANA = [
        '愚者', '魔術師', '女教皇', '女帝', '皇帝', '教皇', '恋人',
        '戦車', '力', '隠者', '運命の輪', '正義', '吊された男', '死神',
        '節制', '悪魔', '塔', '星', '月', '太陽', '審判', '世界',
    ];

    /**
     * 小アルカナ56枚（スート別）
     */
    private const MINOR_ARCANA = [
        'ワンド' => ['エース', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'ページ', 'ナイト', 'クイーン', 'キング'],
        'カップ' => ['エース', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'ページ', 'ナイト', 'クイーン', 'キング'],
        'ソード' => ['エース', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'ページ', 'ナイト', 'クイーン', 'キング'],
        'ペンタクル' => ['エース', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'ページ', 'ナイト', 'クイーン', 'キング'],
    ];

    /**
     * タロットカードを1枚引く
     *
     * @param bool $includeReversed 逆位置を含めるか
     * @return array カード情報
     */
    public function drawOne(bool $includeReversed = true): array
    {
        $allCards = $this->getAllCards();
        $card = $allCards[array_rand($allCards)];
        
        $position = $includeReversed && rand(0, 1) === 1 ? '逆位置' : '正位置';
        
        return [
            'card_name' => $card['name'],
            'card_image' => $this->getCardImagePath($card),
            'message' => $this->getCardMessage($card, $position),
            'position' => $position,
            'category' => $card['category'],
        ];
    }

    /**
     * 全78枚のカードリストを取得
     */
    private function getAllCards(): array
    {
        $cards = [];
        
        // 大アルカナ
        foreach (self::MAJOR_ARCANA as $name) {
            $cards[] = [
                'name' => $name,
                'category' => '大アルカナ',
            ];
        }
        
        // 小アルカナ
        foreach (self::MINOR_ARCANA as $suit => $ranks) {
            foreach ($ranks as $rank) {
                $cards[] = [
                    'name' => "{$suit}の{$rank}",
                    'category' => '小アルカナ',
                    'suit' => $suit,
                    'rank' => $rank,
                ];
            }
        }
        
        return $cards;
    }

    /**
     * カード画像のパスを取得
     */
    private function getCardImagePath(array $card): string
    {
        // 実際の画像パス（storage/app/public/tarot/ などに配置）
        $cardName = str_replace([' ', 'の'], ['_', '_'], $card['name']);
        return "tarot/{$cardName}.jpg";
    }

    /**
     * カードのメッセージを取得（プレースホルダー）
     */
    private function getCardMessage(array $card, string $position): string
    {
        // プレースホルダーメッセージ（後から本物のメッセージに差し替え）
        $messages = [
            '正位置' => [
                '大アルカナ' => "「{$card['name']}」があなたに新しい可能性を示しています。",
                '小アルカナ' => "「{$card['name']}」が今の状況を表しています。",
            ],
            '逆位置' => [
                '大アルカナ' => "「{$card['name']}」が逆位置で、内面の成長を促しています。",
                '小アルカナ' => "「{$card['name']}」が逆位置で、注意が必要な領域を示しています。",
            ],
        ];
        
        return $messages[$position][$card['category']] ?? "「{$card['name']}」があなたにメッセージを伝えています。";
    }

    /**
     * タロットスプレッドを引く
     *
     * @param int $count 引く枚数
     * @return array カード情報の配列
     */
    public function drawSpread(int $count = 3): array
    {
        $cards = [];
        $drawnCards = [];
        
        for ($i = 0; $i < $count; $i++) {
            do {
                $card = $this->drawOne();
            } while (in_array($card['card_name'], $drawnCards));
            
            $drawnCards[] = $card['card_name'];
            $cards[] = $card;
        }
        
        return $cards;
    }
}

