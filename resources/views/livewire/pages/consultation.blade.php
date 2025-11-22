<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-layouts.guest title="個別鑑定について - Fortune Compass">
    <article class="max-w-4xl mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-4xl font-semibold mb-4">個別鑑定について</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                祖乃果による深い鑑定で、あなただけの人生の羅針盤を手に入れませんか。
            </p>
        </header>

        <div class="prose prose-lg dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">個別鑑定の特徴</h2>
                <p class="mb-4">
                    個別鑑定では、あなたの生年月日や出生時刻から、より詳細な分析を行います。
                    オンラインサービスでは伝えきれない、あなただけの深い読み解きをご提供します。
                </p>
                <ul class="list-disc list-inside space-y-2 mb-4">
                    <li>40分コース：じっくりとあなたの人生のテーマや、今後の流れを読み解きます。</li>
                    <li>100分コース：より深く、詳細な鑑定を行います。具体的な質問にもお答えします。</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">鑑定方法</h2>
                <p class="mb-4">
                    ご希望の方法で鑑定を行います：
                </p>
                <ul class="list-disc list-inside space-y-2 mb-4">
                    <li><strong>Zoom鑑定</strong>：画面越しにじっくりとお話ししながら鑑定を行います。</li>
                    <li><strong>電話鑑定</strong>：お電話で鑑定を行います。</li>
                    <li><strong>チャット鑑定</strong>：チャット形式で鑑定を行います。</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">予約方法</h2>
                <p class="mb-4">
                    個別鑑定は、STORESを通じて予約を受け付けています。
                    ご希望の日程と時間をお選びいただき、お申し込みください。
                </p>
                <div class="mt-6">
                    <a href="{{ env('STORES_CONSULTATION_URL', '#') }}" target="_blank" rel="noopener noreferrer" class="inline-block px-6 py-3 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90">
                        STORESで予約する
                    </a>
                </div>
            </section>
        </div>
    </article>
</x-layouts.guest>

