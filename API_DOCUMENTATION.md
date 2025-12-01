# Fortune Compass API ドキュメント

## ベースURL

```
https://your-domain.com/api/v1
```

## 認証

Fortune Compass APIは、Laravel Sanctumを使用したトークンベース認証を採用しています。

### トークンの取得

ログインエンドポイントからトークンを取得し、以降のリクエストで`Authorization`ヘッダーに含めて送信してください。

```
Authorization: Bearer {token}
```

---

## エンドポイント一覧

### 認証

#### POST `/api/v1/auth/login`
ログインしてトークンを取得

**リクエストボディ:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "ユーザー名",
      "email": "user@example.com"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
```

#### POST `/api/v1/auth/logout`
ログアウト（トークンを無効化）

**認証**: 必須

**レスポンス:**
```json
{
  "success": true,
  "message": "ログアウトしました"
}
```

#### GET `/api/v1/auth/me`
現在のユーザー情報を取得

**認証**: 必須

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "ユーザー名",
    "email": "user@example.com",
    "birth_date": "1990-01-01",
    "birth_time": "12:00",
    "birth_place": "東京都"
  }
}
```

---

### 今日の運勢

#### GET `/api/v1/fortunes/today`
今日の運勢を取得

**認証**: 必須

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "date": "2024-11-22",
    "score": 75,
    "theme": "新しい始まりの日",
    "direction": "前向きに進む気持ちを大切に",
    "small_step": "今日は新しいことにチャレンジしてみましょう",
    "four_pillars": { ... },
    "numerology": { ... },
    "ziwei": { ... }
  }
}
```

---

### 感情ログ

#### GET `/api/v1/moods`
感情ログ一覧を取得（ページネーション対応）

**認証**: 必須

**クエリパラメータ:**
- `page` (optional): ページ番号

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 1,
        "date": "2024-11-22",
        "mood_rating": 4,
        "mood_emoji": "😊",
        "memo": "今日は良い日だった",
        "created_at": "2024-11-22T10:00:00.000000Z",
        "updated_at": "2024-11-22T10:00:00.000000Z"
      }
    ],
    "per_page": 30,
    "total": 10
  }
}
```

#### POST `/api/v1/moods`
感情ログを保存

**認証**: 必須

**リクエストボディ:**
```json
{
  "date": "2024-11-22",
  "mood_rating": 4,
  "mood_emoji": "😊",
  "memo": "今日は良い日だった"
}
```

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "date": "2024-11-22",
    "mood_rating": 4,
    "mood_emoji": "😊",
    "memo": "今日は良い日だった",
    "created_at": "2024-11-22T10:00:00.000000Z",
    "updated_at": "2024-11-22T10:00:00.000000Z"
  },
  "message": "感情ログを保存しました"
}
```

#### GET `/api/v1/moods/date/{date}`
特定の日の感情ログを取得

**認証**: 必須

**パスパラメータ:**
- `date`: 日付（YYYY-MM-DD形式）

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "date": "2024-11-22",
    "mood_rating": 4,
    "mood_emoji": "😊",
    "memo": "今日は良い日だった",
    "created_at": "2024-11-22T10:00:00.000000Z",
    "updated_at": "2024-11-22T10:00:00.000000Z"
  }
}
```

#### PUT `/api/v1/moods/{id}`
感情ログを更新

**認証**: 必須

**リクエストボディ:**
```json
{
  "mood_rating": 5,
  "mood_emoji": "😄",
  "memo": "更新されたメモ"
}
```

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "date": "2024-11-22",
    "mood_rating": 5,
    "mood_emoji": "😄",
    "memo": "更新されたメモ",
    "created_at": "2024-11-22T10:00:00.000000Z",
    "updated_at": "2024-11-22T10:01:00.000000Z"
  },
  "message": "感情ログを更新しました"
}
```

#### DELETE `/api/v1/moods/{id}`
感情ログを削除

**認証**: 必須

**レスポンス:**
```json
{
  "success": true,
  "message": "感情ログを削除しました"
}
```

---

### ユーザープロフィール

#### GET `/api/v1/profile`
ユーザープロフィールを取得

**認証**: 必須

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "ユーザー名",
    "email": "user@example.com",
    "birth_date": "1990-01-01",
    "birth_time": "12:00",
    "birth_place": "東京都",
    "email_verified_at": "2024-11-22T10:00:00.000000Z",
    "created_at": "2024-11-22T10:00:00.000000Z",
    "updated_at": "2024-11-22T10:00:00.000000Z"
  }
}
```

#### PUT `/api/v1/profile`
ユーザープロフィールを更新

**認証**: 必須

**リクエストボディ:**
```json
{
  "name": "新しい名前",
  "email": "newemail@example.com",
  "birth_date": "1990-01-01",
  "birth_time": "12:00",
  "birth_place": "東京都"
}
```

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "新しい名前",
    "email": "newemail@example.com",
    "birth_date": "1990-01-01",
    "birth_time": "12:00",
    "birth_place": "東京都"
  },
  "message": "プロフィールを更新しました"
}
```

---

### タロット

#### POST `/api/v1/tarot/draw`
タロットカードを1枚引く

**認証**: 必須

**リクエストボディ（オプション）:**
```json
{
  "include_reversed": true
}
```

**レスポンス:**
```json
{
  "success": true,
  "data": {
    "card_name": "愚者",
    "card_image": "https://your-domain.com/images/tarot/tarot-fool.png",
    "message": "「愚者」があなたに新しい可能性を示しています。",
    "position": "正位置",
    "category": "大アルカナ",
    "suit": null,
    "rank": null
  }
}
```

---

## エラーレスポンス

### 認証エラー（401）
```json
{
  "success": false,
  "message": "認証が必要です"
}
```

### バリデーションエラー（422）
```json
{
  "success": false,
  "message": "バリデーションエラー",
  "errors": {
    "email": ["メールアドレスは必須です"],
    "password": ["パスワードは必須です"]
  }
}
```

### 権限エラー（403）
```json
{
  "success": false,
  "message": "権限がありません"
}
```

### リソースが見つからない（404）
```json
{
  "success": false,
  "message": "リソースが見つかりません"
}
```

---

## 使用例

### cURL

```bash
# ログイン
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# 今日の運勢を取得
curl -X GET https://your-domain.com/api/v1/fortunes/today \
  -H "Authorization: Bearer {token}"

# 感情ログを保存
curl -X POST https://your-domain.com/api/v1/moods \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"date":"2024-11-22","mood_rating":4,"mood_emoji":"😊","memo":"今日は良い日だった"}'
```

### JavaScript (Fetch API)

```javascript
// ログイン
const loginResponse = await fetch('https://your-domain.com/api/v1/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    email: 'user@example.com',
    password: 'password'
  })
});

const { data } = await loginResponse.json();
const token = data.token;

// 今日の運勢を取得
const fortuneResponse = await fetch('https://your-domain.com/api/v1/fortunes/today', {
  headers: {
    'Authorization': `Bearer ${token}`
  }
});

const fortune = await fortuneResponse.json();
```

---

## レート制限

現在、レート制限は設定されていませんが、将来的に追加する予定です。

## バージョン管理

現在のAPIバージョンは `v1` です。将来的な変更は新しいバージョン（`v2`など）として提供される予定です。



