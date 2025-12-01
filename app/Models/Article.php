<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'is_published',
        'published_at',
        'views',
        'order',
        'meta_keywords',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'views' => 'integer',
            'order' => 'integer',
            'meta_keywords' => 'array',
        ];
    }

    /**
     * スラッグを自動生成
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    /**
     * 作成者
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * カテゴリ
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * タグ
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * 公開済みの記事を取得
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    /**
     * 関連記事を取得（同じカテゴリ、同じタグ）
     */
    public function getRelatedArticles(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        $query = static::published()
            ->where('id', '!=', $this->id);

        // 同じカテゴリの記事
        if ($this->category_id) {
            $query->orWhere('category_id', $this->category_id);
        }

        // 同じタグの記事
        if ($this->tags->isNotEmpty()) {
            $tagIds = $this->tags->pluck('id');
            $query->orWhereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        return $query->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * 閲覧数を増やす
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
