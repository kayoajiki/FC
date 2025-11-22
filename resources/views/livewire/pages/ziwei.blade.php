<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-layouts.guest title="紫微斗数とは - Fortune Compass">
    <article class="max-w-4xl mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-4xl font-semibold mb-4">紫微斗数とは</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                出生時刻が必要な、中国の高精度な占星術。詳細な運勢の流れを読み解きます。
            </p>
        </header>

        <div class="prose prose-lg dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">紫微斗数の基本</h2>
                <p class="mb-4">
                    紫微斗数は、生年月日と出生時刻を使って、12の宮位（命宮、兄弟宮、夫妻宮、子女宮、財帛宮、疾厄宮、遷移宮、奴僕宮、官禄宮、田宅宮、福德宮、父母宮）に
                    星を配置し、その組み合わせから運勢を読み解く占術です。
                </p>
                <p class="mb-4">
                    特に出生時刻が重要で、時刻によって命盤（星盤）が大きく変わります。
                    そのため、出生時刻が不明な場合、紫微斗数の診断は行えません。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">12の宮位</h2>
                <p class="mb-4">
                    紫微斗数では、人生を12の領域に分けて分析します：
                </p>
                <ul class="list-disc list-inside space-y-2 mb-4">
                    <li><strong>命宮</strong>：あなたの本質的な性格や性質</li>
                    <li><strong>兄弟宮</strong>：兄弟姉妹との関係</li>
                    <li><strong>夫妻宮</strong>：恋愛や結婚</li>
                    <li><strong>子女宮</strong>：子供との関係や子育て</li>
                    <li><strong>財帛宮</strong>：お金や財運</li>
                    <li><strong>疾厄宮</strong>：健康や病気</li>
                    <li><strong>遷移宮</strong>：移動や転居、環境の変化</li>
                    <li><strong>奴僕宮</strong>：部下や同僚との関係</li>
                    <li><strong>官禄宮</strong>：仕事やキャリア</li>
                    <li><strong>田宅宮</strong>：不動産や住環境</li>
                    <li><strong>福德宮</strong>：精神的な豊かさや幸福感</li>
                    <li><strong>父母宮</strong>：両親との関係</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">星の配置</h2>
                <p class="mb-4">
                    紫微斗数では、主星（紫微、天機、太陽、武曲、天同、廉貞、天府、太陰、貪狼、巨門、天相、天梁、七殺、破軍など）を中心に、
                    さまざまな星が各宮位に配置されます。これらの星の組み合わせから、
                    各領域での運勢の流れや、あなたの強みや注意点を読み解きます。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Fortune Compassでの活かし方</h2>
                <p class="mb-4">
                    Fortune Compassでは、出生時刻を入力していただくことで、紫微斗数の診断結果をご覧いただけます。
                    出生時刻が不明な場合は、「不明」を選択していただけますが、その場合、紫微斗数の診断は表示されません。
                </p>
                <p class="mb-4">
                    無料診断では簡易版をお試しいただけますが、無料登録後は詳細版をご覧いただけます。
                    紫微斗数の詳細な分析結果を日常の判断に活かすことで、より良い人生を送ることができます。
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

