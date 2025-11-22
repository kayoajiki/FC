<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-layouts.guest title="祖乃果について - Fortune Compass">
    <article class="max-w-4xl mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-4xl font-semibold mb-4">祖乃果について</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                Fortune Compassを運営する占い師・祖乃果のプロフィールと想いをご紹介します。
            </p>
        </header>

        <div class="prose prose-lg dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">はじめに</h2>
                <p class="mb-4">
                    はじめまして、祖乃果と申します。
                    Fortune Compassは、「迷ったとき、自分に戻れる光を届ける」をコンセプトに、
                    あなた自身を深く知り、毎日の羅針盤として活用していただくためのサービスです。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">占いとの出会い</h2>
                <p class="mb-4">
                    [ここに祖乃果の占いとの出会いや、なぜ占い師になったかのストーリーを記載します]
                </p>
                <p class="mb-4">
                    [プレースホルダー：実際の文章に差し替えが必要です]
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Fortune Compassへの想い</h2>
                <p class="mb-4">
                    Fortune Compassは、占いを「未来を当てる」ものではなく、
                    「自分を知り、行動を変える羅針盤」として捉えています。
                </p>
                <p class="mb-4">
                    毎日の小さな選択の積み重ねが、大きな人生の流れを作ります。
                    Fortune Compassを通じて、あなた自身を深く理解し、
                    日常の判断に活かしていただくことで、より良い人生を送っていただきたいと考えています。
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">個別鑑定について</h2>
                <p class="mb-4">
                    より深い鑑定をご希望の方は、個別鑑定もご利用いただけます。
                    Zoom、電話、チャットなど、ご希望の方法で鑑定を行います。
                </p>
                <div class="mt-6">
                    <a href="{{ route('consultation') }}" class="inline-block px-6 py-3 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90">
                        個別鑑定について詳しく見る
                    </a>
                </div>
            </section>
        </div>
    </article>
</x-layouts.guest>

