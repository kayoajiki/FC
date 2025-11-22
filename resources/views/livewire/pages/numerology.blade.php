<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-layouts.guest title="数秘術とは - Fortune Compass">
    <article class="max-w-4xl mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-4xl font-semibold mb-4">数秘術とは</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                生年月日から導き出すライフパスナンバーで、あなたの人生のテーマを理解します。
            </p>
        </header>

        <div class="prose prose-lg dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">数秘術の基本</h2>
                <p class="mb-4">
                    数秘術は、生年月日から数字を導き出し、その数字の意味を読み解く占術です。
                    特に「ライフパスナンバー」は、あなたが生まれた使命や人生のテーマを表します。
                </p>
                <p class="mb-4">
                    生年月日の各数字を足し算し、1桁の数字（マスターナンバー11、22、33はそのまま）に還元することで、
                    あなたのライフパスナンバーを導き出します。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">ライフパスナンバーの種類</h2>
                <p class="mb-4">
                    ライフパスナンバーは1〜9、そしてマスターナンバーの11、22、33があります。
                    それぞれの数字には、異なる意味と特徴があります：
                </p>
                <ul class="list-disc list-inside space-y-2 mb-4">
                    <li><strong>1</strong>：リーダーシップと独立性</li>
                    <li><strong>2</strong>：協調性と感受性</li>
                    <li><strong>3</strong>：表現力と創造性</li>
                    <li><strong>4</strong>：実務能力と安定性</li>
                    <li><strong>5</strong>：自由と冒険心</li>
                    <li><strong>6</strong>：責任感と愛情深さ</li>
                    <li><strong>7</strong>：分析力と直感力</li>
                    <li><strong>8</strong>：実力と組織力</li>
                    <li><strong>9</strong>：理想主義と共感性</li>
                    <li><strong>11</strong>：直感力とインスピレーション（マスターナンバー）</li>
                    <li><strong>22</strong>：実践力と建設性（マスターナンバー）</li>
                    <li><strong>33</strong>：慈愛と奉仕（マスターナンバー）</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">マスターナンバー</h2>
                <p class="mb-4">
                    11、22、33は「マスターナンバー」と呼ばれ、特別な使命を持っています。
                    これらの数字は、1桁に還元せず、そのまま使用します。
                </p>
                <p class="mb-4">
                    マスターナンバーを持つ人は、より高い使命や責任を持っているとされています。
                    ただし、その使命を全うするためには、努力と成長が必要です。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Fortune Compassでの活かし方</h2>
                <p class="mb-4">
                    Fortune Compassでは、あなたのライフパスナンバーから、あなたの強み3つと注意点1つを提示します。
                    無料診断では簡易版をお試しいただけますが、無料登録後は詳細版をご覧いただけます。
                </p>
                <p class="mb-4">
                    ライフパスナンバーを理解することで、あなたの人生のテーマを明確にし、
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

