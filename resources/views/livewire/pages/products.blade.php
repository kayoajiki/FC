<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-layouts.guest title="物販について - Fortune Compass">
    <article class="max-w-4xl mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-4xl font-semibold mb-4">物販について</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                自分を整えるアイテムをご用意しています。パワーストーン、PDFガイド、自己理解ブックなど。
            </p>
        </header>

        <div class="prose prose-lg dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">パワーストーン</h2>
                <p class="mb-4">
                    あなたの四柱推命や数秘術の結果に合わせた、最適なパワーストーンをご提案します。
                    パワーストーンは、あなたの運勢をサポートし、日常の気持ちを整える助けとなります。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">PDFガイド</h2>
                <p class="mb-4">
                    四柱推命、紫微斗数、数秘術、タロットの各占術について、
                    より詳しく学べるPDFガイドをご用意しています。
                    あなたの鑑定結果をより深く理解するために、ご活用ください。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">自己理解ブック</h2>
                <p class="mb-4">
                    あなたの鑑定結果をまとめた、オリジナルの自己理解ブックを作成します。
                    日常の判断や選択の際に、いつでも参照できるように、
                    あなただけのブックとしてお手元に置いておくことができます。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">購入方法</h2>
                <p class="mb-4">
                    物販商品は、STORESを通じてご購入いただけます。
                    ご希望の商品をお選びいただき、お申し込みください。
                </p>
                <div class="mt-6">
                    <a href="{{ env('STORES_PRODUCTS_URL', '#') }}" target="_blank" rel="noopener noreferrer" class="inline-block px-6 py-3 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90">
                        STORESで商品を見る
                    </a>
                </div>
            </section>
        </div>
    </article>
</x-layouts.guest>

