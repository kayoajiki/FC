<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-layouts.guest title="タロットとは - Fortune Compass">
    <article class="max-w-4xl mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-4xl font-semibold mb-4">タロットとは</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                78枚のカードから、今この瞬間に必要なメッセージを受け取ります。
            </p>
        </header>

        <div class="prose prose-lg dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">タロットの基本</h2>
                <p class="mb-4">
                    タロットは、78枚のカード（大アルカナ22枚、小アルカナ56枚）から、
                    今この瞬間に必要なメッセージや、未来へのヒントを受け取る占術です。
                </p>
                <p class="mb-4">
                    タロットカードは、単なる未来予測ではなく、あなたの内面や状況を映し出す鏡のようなものです。
                    カードを通じて、自分自身を見つめ直し、必要な行動や選択を導き出すことができます。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">大アルカナと小アルカナ</h2>
                <p class="mb-4">
                    タロットカードは、大きく「大アルカナ」と「小アルカナ」に分かれます：
                </p>
                <ul class="list-disc list-inside space-y-2 mb-4">
                    <li><strong>大アルカナ（22枚）</strong>：人生の大きな流れや、重要なテーマを表します。
                        「愚者」から「世界」まで、人生の旅を象徴するカードです。
                    </li>
                    <li><strong>小アルカナ（56枚）</strong>：日常生活の具体的な場面や、細かな状況を表します。
                        「ワンド」「カップ」「ソード」「ペンタクル」の4つのスートに分かれ、
                        それぞれ「エース」から「キング」まで14枚ずつあります。
                    </li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">正位置と逆位置</h2>
                <p class="mb-4">
                    タロットカードは、正位置と逆位置の両方の意味を持ちます。
                </p>
                <ul class="list-disc list-inside space-y-2 mb-4">
                    <li><strong>正位置</strong>：カードの本来の意味を表します。積極的で明るい側面を示すことが多いです。</li>
                    <li><strong>逆位置</strong>：カードの意味が内面化されたり、注意が必要な側面を示すことが多いです。
                        必ずしも悪い意味ではなく、内面の成長や、注意すべき点を示すこともあります。
                    </li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Fortune Compassでの活かし方</h2>
                <p class="mb-4">
                    Fortune Compassでは、無料診断でタロット1枚引きを体験していただけます。
                    無料登録後は、何度でもタロットを引くことができ、
                    タロットスプレッド（複数のカードを配置した読み方）もご利用いただけます。
                </p>
                <p class="mb-4">
                    タロットカードを通じて、今この瞬間に必要なメッセージを受け取り、
                    日常の判断や選択に活かすことができます。
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

