<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Fortune\TarotService;

class FortuneChatController extends Controller
{
    protected TarotService $tarotService;

    public function __construct(TarotService $tarotService)
    {
        $this->tarotService = $tarotService;
    }

    /**
     * 相談カテゴリーと深掘り質問を取得
     */
    public function categories()
    {
        return response()->json([
            [
                'id' => 'love',
                'label' => '結婚・恋愛',
                'icon' => '💕',
                'questions' => [
                    ['id' => 'feelings', 'label' => 'あの人の気持ち'],
                    ['id' => 'encounter', 'label' => '出会いの時期'],
                    ['id' => 'reunion', 'label' => '復縁の可能性'],
                    ['id' => 'future', 'label' => 'パートナーとの今後'],
                ]
            ],
            [
                'id' => 'work',
                'label' => '仕事',
                'icon' => '💼',
                'questions' => [
                    ['id' => 'career_change', 'label' => '転職を考えている'],
                    ['id' => 'relationship', 'label' => '職場の人間関係'],
                    ['id' => 'evaluation', 'label' => '今の評価への不満'],
                    ['id' => 'talent', 'label' => '自分の才能・適職'],
                ]
            ],
            [
                'id' => 'human_relations',
                'label' => '人間関係',
                'icon' => '🤝',
                'questions' => [
                    ['id' => 'family', 'label' => '家族・親族'],
                    ['id' => 'friend', 'label' => '友人・知人'],
                    ['id' => 'colleague', 'label' => '苦手な上司・同僚'],
                    ['id' => 'general', 'label' => '全体的な対人運'],
                ]
            ],
            [
                'id' => 'self_understanding',
                'label' => '自己理解',
                'icon' => '🧘',
                'questions' => [
                    ['id' => 'strength', 'label' => '自分の強みがわからない'],
                    ['id' => 'passion', 'label' => 'やりたいことが見つからない'],
                    ['id' => 'anxiety', 'label' => 'なんとなく将来が不安'],
                    ['id' => 'fortune_flow', 'label' => '運気の流れを知りたい'],
                ]
            ],
        ]);
    }

    /**
     * 鑑定実行 (タロット1枚引き + モックメッセージ)
     */
    public function consult(Request $request)
    {
        $request->validate([
            'category_id' => 'required|string',
            'question_id' => 'required|string',
        ]);

        // タロットを引く (3枚ドローして1枚選ぶ演出はフロントエンドで行い、ここでは結果を確定させる想定)
        // 実際には、フロントで選んだカードを送信するか、サーバーサイドで選んで返すか。
        // 今回はサーバーサイドで1枚選んで返す（フロントエンドの演出と整合させるならdrawSpread(3)してどれか選ばせるロジックでも可）
        
        $card = $this->tarotService->drawOne();

        // TODO: Bedrock等のAI連携を行い、category_id, question_id, card に基づいたメッセージを生成する
        // 現在はモックレスポンスを返却

        $message = $this->generateMockMessage($request->category_id, $request->question_id, $card);

        return response()->json([
            'card' => $card,
            'message' => $message,
            'action_advice' => $this->generateActionAdvice($request->category_id),
        ]);
    }

    private function generateMockMessage($categoryId, $questionId, $card)
    {
        $cardName = $card['card_name'];
        $position = $card['position'];

        // 簡易的なモックメッセージ生成
        $messages = [
            'love' => "「{$cardName}」の{$position}が出ましたね。恋愛についてのご相談、心が揺れ動いている様子が伝わってきます。このカードは...",
            'work' => "お仕事について、「{$cardName}」の{$position}が示されました。キャリアの岐路において重要な意味を持つカードです...",
            'human_relations' => "人間関係のお悩みですね。「{$cardName}」の{$position}は、周囲との調和や距離感について示唆を与えてくれています...",
            'self_understanding' => "ご自身と向き合う良い機会です。「{$cardName}」の{$position}が、あなたの内なる声や本来の強みを映し出しています...",
        ];

        return $messages[$categoryId] ?? "鑑定結果をお伝えします。「{$cardName}」の{$position}が出ています。";
    }

    private function generateActionAdvice($categoryId)
    {
        $advices = [
            'love' => ['title' => '自分をいたわる時間を作ろう', 'body' => 'まずは温かいお茶を飲んでリラックスを。焦りは禁物です。'],
            'work' => ['title' => '優先順位の整理を', 'body' => 'タスクを書き出し、本当に重要なものに集中してみましょう。'],
            'human_relations' => ['title' => '距離感を再確認', 'body' => '無理に合わせすぎず、自分のペースを大切にしてください。'],
            'self_understanding' => ['title' => '感情のジャーナリング', 'body' => '今の気持ちを紙に書き出すことで、思考がクリアになります。'],
        ];

        return $advices[$categoryId] ?? ['title' => '深呼吸を一つ', 'body' => 'まずは一息ついて、落ち着いて状況を見渡しましょう。'];
    }
}
