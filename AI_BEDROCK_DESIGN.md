# AI Bedrock 統合設計（Fortune Compass）

> **「朝の光」トーンを守りながら、温かみのある鑑定チャットを実現**

---

## 🎯 概要

AWS Bedrockを活用して、Fortune Compassの「朝の光」世界観に合わせた、温かみのあるAI鑑定チャット機能を実装します。

### 目的

- ユーザーの気持ちを整理するサポート
- 行動ヒントの提供
- 迷いの根っこの分析
- 週次Insight自動生成
- 「朝の光」トーンを守った、温かみのある対話

---

## 🏗️ アーキテクチャ設計

### 1. 技術スタック

```
【バックエンド】
- Laravel 12
- AWS SDK for PHP (Bedrock)
- Claude 3 Sonnet / Haiku（推奨モデル）

【フロントエンド】
- Livewire v3 / Volt（チャットUI）
- Tailwind CSS（「朝の光」トーン）

【データベース】
- ai_chat_conversations（会話履歴）
- ai_chat_messages（メッセージ履歴）
```

### 2. ディレクトリ構造

```
app/
├── Services/
│   └── AI/
│       ├── BedrockService.php（Bedrock統合）
│       ├── ChatService.php（チャット機能）
│       ├── PromptBuilder.php（プロンプト構築）
│       └── InsightService.php（週次Insight生成）
├── Models/
│   ├── AiChatConversation.php
│   └── AiChatMessage.php
└── Http/
    └── Controllers/
        └── Api/
            └── AiChatController.php
```

---

## 💬 プロンプト設計（「朝の光」トーン）

### 1. システムプロンプト（基本）

```
あなたは「Fortune Compass」という占いプラットフォームのAIアシスタントです。

【世界観】
- 「迷ったとき、自分に戻れる光を届ける」が使命
- 「朝の光のような静かで優しいトーン」を守る
- 不安を煽らず、寄り添うスタイル

【トーン】
- 静かで優しい
- 押しつけがましくない
- 一緒に考える伴走スタイル
- 「今日の一歩」が見える文章
- 誰でも理解できる明瞭さ

【避ける表現】
- 不安を煽る言い方
- 命令・圧
- 断定的な未来予測
- スピリチュアル過剰
- ポジティブ強要
- 派手すぎる表現

【ユーザー情報】
- 四柱推命の結果：{four_pillars_result}
- 数秘術の結果：{numerology_result}
- 紫微斗数の結果：{ziwei_result}
- 感情ログの傾向：{mood_trends}
- 今日の運勢：{daily_fortune}

これらの情報を参考に、ユーザーに寄り添う回答をしてください。
```

### 2. 機能別プロンプト

#### A. 気持ちの整理

```
ユーザーが今感じている気持ちを整理するサポートをしてください。

【回答のポイント】
- まず、ユーザーの気持ちに共感する
- 気持ちを整理するための質問を投げかける
- 「今日の一歩」が見えるアドバイスを提供
- 押しつけがましくない、優しいトーンで

【例】
「その気持ち、よく分かります。まずは、今一番大切にしたいことは何でしょうか？
小さな一歩でいいので、今日できることを一緒に考えてみませんか？」
```

#### B. 行動ヒント

```
ユーザーの状況に合わせた、具体的な行動ヒントを提供してください。

【回答のポイント】
- ユーザーの命式・運勢を考慮する
- 実践的で具体的なアドバイス
- 「朝の光」トーンで、優しく前向きに
- 小さな一歩から始められる提案

【例】
「あなたの強みを活かすなら、○○から始めてみるのはいかがでしょうか？
無理をしなくていいので、まずは小さな一歩から。」
```

#### C. 迷いの根っこ分析

```
ユーザーの迷いの根本的な原因を分析し、優しく伝えてください。

【回答のポイント】
- ユーザーの命式・性格傾向を考慮する
- 迷いの根本原因を優しく分析
- 解決のヒントを提供
- 不安を煽らず、寄り添うスタイル

【例】
「その迷い、もしかすると○○が原因かもしれません。
でも、それはあなたの優しさから来ているものかもしれません。
まずは、自分が本当に大切にしたいことを整理してみませんか？」
```

#### D. 週次Insight自動生成

```
ユーザーの1週間の感情ログ・運勢を分析し、週次Insightを生成してください。

【分析ポイント】
- 感情ログの傾向（週平均、変化）
- 今週の運勢のテーマ
- 来週へのアドバイス
- 「朝の光」トーンで、優しく前向きに

【例】
「今週は、感情の波があったようですね。
でも、それは成長の証かもしれません。
来週は、○○を意識してみると、心が整いやすくなるかもしれません。」
```

---

## 🗄️ データベース設計

### 1. ai_chat_conversations

```php
Schema::create('ai_chat_conversations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('title')->nullable(); // 会話のタイトル（自動生成）
    $table->string('type')->default('general'); // general, mood_analysis, action_hint, etc.
    $table->json('context')->nullable(); // ユーザーの命式・感情ログなどのコンテキスト
    $table->timestamps();
});
```

### 2. ai_chat_messages

```php
Schema::create('ai_chat_messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('conversation_id')->constrained('ai_chat_conversations')->onDelete('cascade');
    $table->enum('role', ['user', 'assistant']);
    $table->text('content');
    $table->json('metadata')->nullable(); // 追加情報（トークン数、モデル名など）
    $table->timestamps();
});
```

---

## 🔧 実装詳細

### 1. BedrockService（Bedrock統合）

```php
namespace App\Services\AI;

use Aws\BedrockRuntime\BedrockRuntimeClient;
use Illuminate\Support\Facades\Log;

class BedrockService
{
    protected BedrockRuntimeClient $client;
    protected string $modelId = 'anthropic.claude-3-sonnet-20240229-v1:0'; // または Haiku

    public function __construct()
    {
        $this->client = new BedrockRuntimeClient([
            'region' => env('AWS_REGION', 'us-east-1'),
            'version' => 'latest',
        ]);
    }

    public function invoke(string $prompt, array $context = []): string
    {
        try {
            $response = $this->client->invokeModel([
                'modelId' => $this->modelId,
                'contentType' => 'application/json',
                'accept' => 'application/json',
                'body' => json_encode([
                    'anthropic_version' => 'bedrock-2023-05-31',
                    'max_tokens' => 2000,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'system' => $this->buildSystemPrompt($context),
                ]),
            ]);

            $responseBody = json_decode($response['body'], true);
            return $responseBody['content'][0]['text'] ?? '';

        } catch (\Exception $e) {
            Log::error('Bedrock API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function buildSystemPrompt(array $context): string
    {
        // 「朝の光」トーンのシステムプロンプトを構築
        // ユーザーの命式・感情ログなどのコンテキストを含める
    }
}
```

### 2. ChatService（チャット機能）

```php
namespace App\Services\AI;

use App\Models\AiChatConversation;
use App\Models\AiChatMessage;
use App\Models\User;

class ChatService
{
    protected BedrockService $bedrock;
    protected PromptBuilder $promptBuilder;

    public function __construct(BedrockService $bedrock, PromptBuilder $promptBuilder)
    {
        $this->bedrock = $bedrock;
        $this->promptBuilder = $promptBuilder;
    }

    public function sendMessage(User $user, string $message, ?int $conversationId = null): array
    {
        // 会話の取得 or 新規作成
        $conversation = $conversationId
            ? AiChatConversation::findOrFail($conversationId)
            : $this->createConversation($user);

        // ユーザーメッセージを保存
        $userMessage = AiChatMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $message,
        ]);

        // コンテキストを構築（ユーザーの命式・感情ログなど）
        $context = $this->buildContext($user);

        // プロンプトを構築
        $prompt = $this->promptBuilder->build($message, $context, $conversation);

        // Bedrockで回答を生成
        $response = $this->bedrock->invoke($prompt, $context);

        // AIメッセージを保存
        $aiMessage = AiChatMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $response,
        ]);

        return [
            'conversation_id' => $conversation->id,
            'user_message' => $userMessage,
            'ai_message' => $aiMessage,
        ];
    }

    protected function buildContext(User $user): array
    {
        // ユーザーの命式・感情ログなどのコンテキストを構築
        return [
            'four_pillars' => $this->getFourPillarsResult($user),
            'numerology' => $this->getNumerologyResult($user),
            'ziwei' => $this->getZiweiResult($user),
            'mood_trends' => $this->getMoodTrends($user),
            'daily_fortune' => $this->getDailyFortune($user),
        ];
    }
}
```

### 3. PromptBuilder（プロンプト構築）

```php
namespace App\Services\AI;

class PromptBuilder
{
    protected string $systemPromptTemplate;

    public function build(string $userMessage, array $context, $conversation): string
    {
        // システムプロンプトを構築
        $systemPrompt = $this->buildSystemPrompt($context);

        // 会話履歴を含める
        $conversationHistory = $this->buildConversationHistory($conversation);

        // 最終的なプロンプトを構築
        return $this->formatPrompt($systemPrompt, $conversationHistory, $userMessage);
    }

    protected function buildSystemPrompt(array $context): string
    {
        // 「朝の光」トーンのシステムプロンプトを構築
        // ユーザーの命式・感情ログなどのコンテキストを含める
    }
}
```

---

## 🎨 UI設計

### 1. チャットUI（Livewire/Volt）

```php
// resources/views/livewire/volt/ai-chat.blade.php
<div class="flex flex-col h-full">
    <!-- チャットヘッダー -->
    <div class="border-b p-4">
        <h2 class="text-lg font-semibold">AIコンパス</h2>
        <p class="text-sm text-gray-600">気持ちを整理する、あなたの伴走者</p>
    </div>

    <!-- メッセージエリア -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4">
        @foreach($messages as $message)
            <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->role === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800' }}">
                    {{ $message->content }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- 入力エリア -->
    <div class="border-t p-4">
        <form wire:submit="sendMessage">
            <input type="text" wire:model="message" placeholder="気持ちを整理したいこと、聞いてみたいことを入力..." class="w-full px-4 py-2 border rounded-lg">
            <button type="submit" class="mt-2 w-full bg-blue-500 text-white py-2 rounded-lg">送信</button>
        </form>
    </div>
</div>
```

---

## 🔐 セキュリティ・プライバシー

### 1. データ保護

- 会話履歴はユーザーごとに完全に分離
- 個人情報は暗号化して保存
- AI学習には使用しない（明記）

### 2. レート制限

- 1ユーザーあたりの1日のメッセージ数を制限
- サブスクユーザーは制限を緩和

### 3. エラーハンドリング

- Bedrock APIのエラーを適切に処理
- フォールバックメッセージを用意

---

## 📊 期待される効果

- **エンゲージメント向上**: ユーザーが継続的に利用したくなる
- **サブスク転換**: AIチャット機能により、サブスク登録への動機づけ
- **ユーザー満足度**: 「朝の光」トーンで、温かみのある体験

---

## 🚀 実装の優先順位

### P2-2で実装

1. **Bedrock統合**（基盤）
   - AWS SDK for PHPのインストール
   - BedrockServiceの実装
   - 環境変数の設定

2. **チャット機能**（基本）
   - ChatServiceの実装
   - データベース設計
   - 基本的なチャットUI

3. **プロンプト設計**（「朝の光」トーン）
   - PromptBuilderの実装
   - システムプロンプトの調整
   - コンテキストの構築

4. **拡張機能**（将来）
   - 週次Insight自動生成
   - 機能別プロンプトの実装
   - UIの改善

---

## 📝 環境変数設定

```env
# AWS Bedrock
AWS_REGION=us-east-1
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
BEDROCK_MODEL_ID=anthropic.claude-3-sonnet-20240229-v1:0

# レート制限
AI_CHAT_DAILY_LIMIT_FREE=10
AI_CHAT_DAILY_LIMIT_PREMIUM=100
```

---

## ✅ 次のステップ

1. **P2-2実装時**:
   - AWS Bedrock SDKのインストール
   - BedrockServiceの実装
   - 基本的なチャット機能の実装

2. **開発中に調整**:
   - プロンプトの調整（「朝の光」トーンの最適化）
   - UIの改善
   - エラーハンドリングの強化

3. **将来の拡張**:
   - 週次Insight自動生成
   - 機能別プロンプトの実装
   - より高度なコンテキストの活用

