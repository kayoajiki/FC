<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

@php
    $title = '四柱推命とは - Fortune Compass';
    $description = '四柱推命は、生年月日と時刻から、あなたの本質的な性質や運勢の流れを読み解く中国の伝統的な占術です。Fortune Compassで無料診断を受けてみましょう。';
@endphp

<x-layouts.guest 
    :title="$title"
    :description="$description"
    :ogType="'article'"
    :ogTitle="$title"
    :ogDescription="$description"
>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "四柱推命とは",
        "description": "{{ $description }}",
        "author": {
            "@type": "Person",
            "name": "Fortune Compass"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Fortune Compass",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/logo.png') }}"
            }
        },
        "datePublished": "2024-01-01",
        "dateModified": "{{ now()->format('Y-m-d') }}"
    }
    </script>

    <article class="max-w-4xl mx-auto px-4 py-12">
        <x-breadcrumbs :items="[
            ['label' => 'ホーム', 'url' => route('home')],
            ['label' => '四柱推命とは']
        ]" />
        
        <header class="mb-8">
            <h1 class="text-4xl font-semibold mb-4">四柱推命とは</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                生年月日と時刻から、あなたの本質的な性質や運勢の流れを読み解く中国の伝統的な占術です。
            </p>
        </header>

        <div class="prose prose-lg dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">四柱推命の基本</h2>
                <p class="mb-4">
                    四柱推命は、生年月日と出生時刻を「年柱」「月柱」「日柱」「時柱」の4つの柱に分け、
                    それぞれの干支（十干十二支）の組み合わせから、あなたの性格や運勢を読み解く占術です。
                </p>
                <p class="mb-4">
                    この4つの柱を分析することで、あなたの本質的な性質、適性、人生の流れを深く理解することができます。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">五行バランス</h2>
                <p class="mb-4">
                    四柱推命では、すべてのものを「木」「火」「土」「金」「水」の5つの元素（五行）に分類します。
                    あなたの生年月日から、これらの五行のバランスを分析することで、
                    あなたの性格の特徴や、日常生活での注意点を読み解きます。
                </p>
                <p class="mb-4">
                    五行のバランスが整っていると、人生の流れもスムーズになります。
                    一方で、特定の五行が不足している場合や過剰な場合には、
                    その影響を理解し、日常生活で意識することで、より良い人生を送ることができます。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">日干から見る本質</h2>
                <p class="mb-4">
                    四柱推命では、特に「日干」が重要視されます。
                    日干は、あなたの生まれた日の天干で、あなたの本質的な性格や性質を表します。
                </p>
                <p class="mb-4">
                    日干は「甲」「乙」「丙」「丁」「戊」「己」「庚」「辛」「壬」「癸」の10種類に分類され、
                    それぞれ異なる性質を持っています。あなたの日干を理解することで、
                    自分自身をより深く知ることができ、日常生活での判断や選択にも活かすことができます。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Fortune Compassでの活かし方</h2>
                <p class="mb-4">
                    Fortune Compassでは、四柱推命の診断結果から、あなたの強み3つと注意点1つを提示します。
                    無料診断では簡易版をお試しいただけますが、無料登録後は詳細版をご覧いただけます。
                </p>
                <p class="mb-4">
                    また、今日の運勢や感情ログと組み合わせることで、
                    四柱推命の結果を日常の判断に活かすことができます。
                </p>
            </section>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90">
                無料診断を試す
            </a>
        </div>
    </article>
</x-layouts.guest>

