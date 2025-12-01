<?php

namespace App\Services\Article;

use App\Models\Article;

/**
 * 記事内の内部リンクを自動生成するサービス
 */
class InternalLinkService
{
    /**
     * 内部リンクに変換するキーワードとそのリンク先
     */
    private array $keywordLinks = [
        '四柱推命' => ['url' => '/four-pillars', 'title' => '四柱推命について'],
        '紫微斗数' => ['url' => '/ziwei', 'title' => '紫微斗数について'],
        '数秘術' => ['url' => '/numerology', 'title' => '数秘術について'],
        'タロット' => ['url' => '/tarot', 'title' => 'タロットについて'],
        'Fortune Compass' => ['url' => '/', 'title' => 'Fortune Compass'],
    ];

    /**
     * 記事コンテンツ内のキーワードを内部リンクに変換
     */
    public function generateLinks(string $content, ?Article $currentArticle = null): string
    {
        $processedContent = $content;

        // 各キーワードをリンクに変換
        foreach ($this->keywordLinks as $keyword => $link) {
            // 既にリンクになっている場合はスキップ
            $pattern = '/(?<!<a[^>]*>)(?<!href=["\'])' . preg_quote($keyword, '/') . '(?!["\']>)(?![^<]*<\/a>)/u';
            
            $replacement = sprintf(
                '<a href="%s" title="%s" class="text-peach hover:text-peach/80 underline">%s</a>',
                $link['url'],
                $link['title'],
                $keyword
            );

            // 最初の1回だけ置換（同じキーワードが複数回出現する場合、最初の1回だけリンク化）
            $processedContent = preg_replace($pattern, $replacement, $processedContent, 1);
        }

        // 記事タイトルへのリンク（他の記事内で言及されている場合）
        if ($currentArticle) {
            $articles = Article::published()
                ->where('id', '!=', $currentArticle->id)
                ->get();

            foreach ($articles as $article) {
                // 記事タイトルが本文内に出現する場合、その記事へのリンクに変換
                $pattern = '/(?<!<a[^>]*>)(?<!href=["\'])' . preg_quote($article->title, '/') . '(?!["\']>)(?![^<]*<\/a>)/u';
                $replacement = sprintf(
                    '<a href="%s" title="%s" class="text-peach hover:text-peach/80 underline">%s</a>',
                    route('column.show', $article->slug),
                    $article->title,
                    $article->title
                );

                // 最初の1回だけ置換
                $processedContent = preg_replace($pattern, $replacement, $processedContent, 1);
            }
        }

        return $processedContent;
    }

    /**
     * キーワードリンクの設定を追加
     */
    public function addKeywordLink(string $keyword, string $url, string $title): void
    {
        $this->keywordLinks[$keyword] = [
            'url' => $url,
            'title' => $title,
        ];
    }
}



