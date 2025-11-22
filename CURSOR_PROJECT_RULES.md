# Cursor プロジェクトルール（Fortune Compass）

> **Fortune Compass 開発における、Cursorが守るべき技術的ルールと判断基準**

---

## 🎯 基本方針

- **モノリシック Laravel**: API・Web・UIロジックは同一リポジトリ
- **Service層優先**: 複雑ロジックは必ずService層へ集約
- **小さく始める**: 最初はシンプルに、必要に応じて拡張
- **「朝の光」トーン**: UI・UX・メッセージは常に「朝の光」トーンを守る

---

## 1. バックエンド（API・ロジック）

### ✅ ルール

1. **すべて Laravel を使う**
   - バックエンドのロジックはすべてLaravel（PHP）で実装
   - Node.js / Bun をAPIサーバーとしては使わない

2. **コントローラの配置**
   - Web用: `app/Http/Controllers/`
   - API用: `app/Http/Controllers/Api/`
   - 例: `App\Http\Controllers\Api\FortuneController`

3. **API のルーティング**
   - APIは `routes/api.php` で作成
   - バージョン付きにする: `/api/v1/...`
   - 将来 `/v2` を増やせるように設計

4. **占いロジックは必ず Service層へ**
   - 占い計算ロジックは `app/Services/Fortune/` に配置
   - 例: `FourPillarsService`, `NumerologyService`, `ZiweiService`, `TarotService`
   - ControllerやComponentに直接ロジックを書かない

5. **Volt / Livewire コンポーネント内に複雑ロジックを書かない**
   - コンポーネントは表示と状態管理のみ
   - ビジネスロジックはService層に委譲

### 📝 実装例

```php
// ❌ 悪い例：Controllerに直接ロジック
class FortuneController extends Controller
{
    public function today()
    {
        // 占い計算ロジックを直接書く（NG）
        $result = /* 複雑な計算 */;
        return view('fortune.today', ['result' => $result]);
    }
}

// ✅ 良い例：Service層に委譲
class FortuneController extends Controller
{
    public function today(DailyFortuneService $service)
    {
        $result = $service->forUser(auth()->user())->today();
        return view('fortune.today', ['result' => $result]);
    }
}
```

---

## 2. フロントエンド

### ✅ ルール

1. **画面レンダリングは基本 Blade**
   - 静的ページ、文章中心のページ
   - LP、ヘルプ、規約など

2. **動的UIは Livewire v3 を使用**
   - 複雑な画面
   - 画面全体で状態を持つもの
   - タブやフィルタ、ページネーションなど

3. **小型のUI部品や一覧・入力画面は Volt を優先**
   - 小さめのコンポーネント（フォーム、カード、一覧）
   - 状態を持つが、1ファイルで完結できるレベルのもの
   - カレンダーの1日セル・小さなカード・フォームUIなど

4. **リアクティブ動作は Alpine.js も併用可**
   - 軽量なインタラクション
   - モーダル、ドロップダウンなど

5. **Tailwind CSS を標準で使用**
   - カスタムCSSは最小限に
   - Fortune CompassのカラーパレットをTailwind設定に追加

### 📝 使い分けの判断基準

| ケース | 推奨手段 |
|--------|---------|
| 静的ページ（紹介文など） | Blade |
| 「今日の運勢」カード | Volt |
| MoodDiary の1日入力モーダル | Volt |
| ダッシュボード（複数カード・グラフなど） | Livewire（必要なら一部Volt） |
| 管理画面（一覧＋検索＋ページネーション） | Livewire |
| ユーザー設定フォーム | Volt or Livewire（項目数で判断） |

---

## 3. Volt を使う判断ルール

### ✅ Volt を使う場合

- **UIの状態管理が必要**
  - フォームの入力状態
  - モーダルの開閉状態
  - タブの切り替え状態

- **小規模コンポーネント**
  - 1ファイルで完結できるレベル
  - 100行程度以内

- **Livewire のコントローラとBladeファイルを分けるほどでもない場合**
  - シンプルなコンポーネント
  - 状態管理が軽量

### 📝 実装例

```php
// ✅ Volt の良い例：小さなフォームコンポーネント
<?php

use Livewire\Volt\Component;

new class extends Component
{
    public string $message = '';

    public function send()
    {
        // Service層に委譲
        app(ChatService::class)->send($this->message);
        $this->message = '';
    }
}
?>

<div>
    <input wire:model="message" type="text">
    <button wire:click="send">送信</button>
</div>
```

---

## 4. Volt を使わない判断ルール

### ❌ Volt を使わない場合

- **画面の複雑度が高い**
  - 複数のセクション
  - 複雑な状態管理
  - 多くの子コンポーネント

- **ロジックが重い（占いロジックなど）**
  - 占い計算
  - データ処理
  - 複雑なビジネスロジック
  → Service層に委譲し、通常のLivewire or Controllerを使用

- **APIとして別フロントからも叩かれる**
  - 外部アプリからも利用される機能
  → Controller + APIルートを使用

### 📝 実装例

```php
// ✅ 通常のLivewire：複雑な画面
namespace App\Livewire;

use Livewire\Component;
use App\Services\Fortune\DailyFortuneService;

class Dashboard extends Component
{
    public function render(DailyFortuneService $service)
    {
        return view('livewire.dashboard', [
            'todayFortune' => $service->forUser(auth()->user())->today(),
            // 他のデータも取得
        ]);
    }
}
```

---

## 5. Bun の利用ルール

### ✅ Bun を使う場面（OK）

1. **npm / node 管理**
   ```bash
   bun install
   ```

2. **Vite の build / dev**
   ```bash
   bun run dev
   bun run build
   ```

3. **パッケージ管理**
   - `package.json` の依存関係管理
   - npm互換のパッケージのインストール

### ❌ Bun を使わない場面（NG）

1. **サーバーサイド（API）の実行に Bun を使用しない**
   - APIサーバーはLaravel（PHP）で実装
   - BunをAPIサーバーとして使わない

2. **Node 専用 API を前提としたパッケージを選ばない**
   - Laravel標準機能で代替可能か確認
   - どうしても必要な場合のみ検討

### 📝 package.json の例

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build"
  },
  "dependencies": {
    "@tailwindcss/vite": "^4.1.11",
    "autoprefixer": "^10.4.20",
    "axios": "^1.7.4",
    "laravel-vite-plugin": "^2.0",
    "tailwindcss": "^4.0.7",
    "vite": "^7.0.4"
  }
}
```

---

## 6. 全体アーキテクチャ

### ✅ アーキテクチャ方針

1. **モノリシック Laravel**
   - API・Web・UIロジックは同一リポジトリ
   - 1つのLaravelプロジェクトにまとめる

2. **複雑ロジックは Services 層へ集約**
   - `app/Services/` 配下に機能別に整理
   - 例: `app/Services/Fortune/`, `app/Services/AI/`

3. **本番は Lightsail（Docker なし）**
   - AWS Lightsail上のLaravel / LAMPイメージ
   - Docker/ECS前提の複雑な構成は提案しない

4. **ローカルは Laravel Sail（Docker）または Bun + Vite**
   - 開発環境は柔軟に対応
   - どちらでも動くように、Docker前提のコードにはしない

### 📁 ディレクトリ構造

```
app/
├── Http/
│   └── Controllers/
│       ├── Controller.php
│       └── Api/
│           └── FortuneController.php
├── Services/
│   ├── Fortune/
│   │   ├── FourPillarsService.php
│   │   ├── NumerologyService.php
│   │   ├── ZiweiService.php
│   │   └── TarotService.php
│   └── AI/
│       ├── BedrockService.php
│       └── ChatService.php
├── Models/
│   └── User.php
└── Livewire/
    └── Dashboard.php

resources/
├── views/
│   ├── components/
│   ├── livewire/
│   │   └── volt/
│   └── dashboard.blade.php
└── js/
    └── app.js

routes/
├── web.php
└── api.php
```

---

## 7. コーディング規約

### ✅ 一般的なルール

1. **クラス名・メソッド名は PSR-12 に準拠**
   - 一般的なLaravel慣習を守る

2. **型宣言は可能な限り付ける**
   - 戻り値・プロパティも含む
   - 例: `public function today(): object`

3. **コメントやメソッドの説明は日本語で**
   - 簡潔に、わかりやすく

4. **1ファイルが大きくなりすぎた場合（300行以上目安）**
   - 分割・Service抽出を積極的に提案

---

## 8. 画面例ごとの推奨アプローチ

### 📋 具体的な画面例

| 画面/機能 | 推奨アプローチ |
|----------|--------------|
| 今日の運勢ダッシュボードカード | Volt + Service呼び出し |
| MoodDiaryカレンダー（月表示） | Livewireメイン（一部の日セルをVoltにしても良い） |
| 命式・命盤詳細画面 | 通常Blade + 一部Livewire（タブなど動的部分） |
| ユーザー設定（生年月日・出生時間・出生地） | Volt or Livewire |
| API: `/api/v1/fortunes/today` | Laravel API + Service |
| AIチャットUI | Livewire or Volt（会話履歴の管理が必要な場合はLivewire） |

---

## 9. 開発時の注意点

### ⚠️ Cursorが守るべきこと

1. **既存構造を尊重する**
   - すでにあるディレクトリ構成・命名規則に合わせる
   - 新しいパターンを持ち込む前に、似たファイルがないか検索

2. **Service層を優先する**
   - 占いロジックは必ず `app/Services/Fortune/` に配置
   - Controller / Component に直書きしない

3. **小さく変更する**
   - 1回の変更は「1画面 or 1機能」に絞る
   - diffが大きくなりすぎないようにする

4. **依存パッケージを安易に増やさない**
   - 新規ライブラリを追加提案する場合
   - 「本当にLaravel標準機能で代替できないか？」を確認

5. **わかりやすいサンプルを出す**
   - コード例は可能な限り具体的に
   - ルーティング〜Controller〜Service〜Viewが繋がる形で提示

---

## 10. 環境変数・設定

### 📝 .env の例

```env
# アプリケーション
APP_NAME="Fortune Compass"
APP_ENV=local
APP_DEBUG=true

# データベース
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# AWS Bedrock（P2で使用）
AWS_REGION=us-east-1
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
BEDROCK_MODEL_ID=anthropic.claude-3-sonnet-20240229-v1:0

# 外部リンク（後から設定）
LINE_URL=https://line.me/...
X_URL=https://x.com/...
INSTAGRAM_URL=https://instagram.com/...
NOTE_URL=https://note.com/...
COCORARA_URL=https://cocorara.jp/...
STORES_URL=https://stores.jp/...
```

---

## ✅ チェックリスト

開発時に確認すべきこと：

- [ ] 占いロジックはService層に配置されているか？
- [ ] Volt/Livewireの使い分けは適切か？
- [ ] ControllerやComponentに複雑ロジックを書いていないか？
- [ ] APIは `/api/v1/...` 形式になっているか？
- [ ] Bunは適切な場面でのみ使用しているか？
- [ ] 「朝の光」トーンを守っているか？

---

## 📚 関連ドキュメント

- `DEVELOPMENT_ROADMAP.md` - 開発ロードマップ
- `SEO_AND_CONVERSION_STRATEGY.md` - SEO対策・離脱率最小化戦略
- `AI_BEDROCK_DESIGN.md` - AI Bedrock統合設計
- `DEVELOPMENT_QUESTIONS.md` - 開発前の確認事項

---

**更新日**: 2025-01-XX  
**次回レビュー**: 開発開始時

