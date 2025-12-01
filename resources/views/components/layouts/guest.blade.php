<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $pageTitle = $title ?? 'Fortune Compass - 迷ったとき、自分に戻れる光を届ける';
            $pageDescription = $description ?? 'Fortune Compassは、四柱推命・紫微斗数・数秘術・タロットの4つの占術を通じて、あなた自身を深く知り、毎日の羅針盤として活用できるサービスです。';
            $pageOgType = $ogType ?? 'website';
            $pageOgTitle = $ogTitle ?? $pageTitle;
            $pageOgDescription = $ogDescription ?? $pageDescription;
        @endphp
        
        @include('partials.head', [
            'title' => $pageTitle,
            'description' => $pageDescription,
            'ogType' => $pageOgType,
            'ogTitle' => $pageOgTitle,
            'ogDescription' => $pageOgDescription,
            'ogUrl' => $ogUrl ?? url()->current(),
            'ogImage' => $ogImage ?? null,
        ])
        
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <!-- 構造化データ（JSON-LD） -->
        @php
            $jsonLd = [
                "@context" => "https://schema.org",
                "@type" => $pageOgType === 'article' ? 'Article' : 'WebSite',
                "name" => "Fortune Compass",
                "url" => url()->current(),
                "headline" => $pageOgTitle,
                "description" => $pageOgDescription,
                "potentialAction" => [
                    "@type" => "SearchAction",
                    "target" => url('/') . "?s={search_term_string}",
                    "query-input" => "required name=search_term_string"
                ]
            ];

            if ($pageOgType === 'article') {
                $jsonLd['author'] = [
                    "@type" => "Person",
                    "name" => "Fortune Compass"
                ];
                $jsonLd['publisher'] = [
                    "@type" => "Organization",
                    "name" => "Fortune Compass"
                ];
            }
        @endphp
        <script type="application/ld+json">
            {!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
        @stack('head-scripts')
    </head>
    <body class="antialiased" style="background-color: #FFFDF9; color: #2A2E47; font-family: 'Noto Sans JP', 'Inter', sans-serif;">
        <header class="w-full max-w-7xl mx-auto px-4 py-6">
            <nav class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                    Fortune Compass
                </a>
                <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm hover:text-[#F8A38A] transition-colors" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif;">ログイン</a>
                </div>
            </nav>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="w-full max-w-7xl mx-auto px-4 py-12 mt-20 border-t" style="border-color: rgba(248, 163, 138, 0.2); background-color: #FFFDF9;">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <!-- サービス情報 -->
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                        Fortune Compass
                    </h3>
                    <p class="text-sm mb-4" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                        迷ったとき、自分に戻れる光を届ける<br>
                        四柱推命・紫微斗数・数秘術・タロットの4つの占術を通じて、<br>
                        あなた自身を深く知り、毎日の羅針盤として活用できるサービスです。
                    </p>
                </div>

                <!-- リンク -->
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                        リンク
                    </h3>
                    <ul class="space-y-2 text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                        <li><a href="{{ route('home') }}" class="hover:text-[#F8A38A] transition-colors">ホーム</a></li>
                        <li><a href="{{ route('column.index') }}" class="hover:text-[#F8A38A] transition-colors">コラム</a></li>
                        <li><a href="{{ route('four-pillars') }}" class="hover:text-[#F8A38A] transition-colors">四柱推命について</a></li>
                        <li><a href="{{ route('ziwei') }}" class="hover:text-[#F8A38A] transition-colors">紫微斗数について</a></li>
                        <li><a href="{{ route('numerology') }}" class="hover:text-[#F8A38A] transition-colors">数秘術について</a></li>
                        <li><a href="{{ route('tarot') }}" class="hover:text-[#F8A38A] transition-colors">タロットについて</a></li>
                    </ul>
                </div>

                <!-- SNSリンク -->
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                        フォローする
                    </h3>
                    <div class="flex gap-4">
                        <a href="https://twitter.com/FortuneCompass" target="_blank" rel="noopener noreferrer" class="text-2xl hover:text-[#F8A38A] transition-colors" aria-label="Twitter">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/fortunecompass" target="_blank" rel="noopener noreferrer" class="text-2xl hover:text-[#F8A38A] transition-colors" aria-label="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="https://www.youtube.com/@fortunecompass" target="_blank" rel="noopener noreferrer" class="text-2xl hover:text-[#F8A38A] transition-colors" aria-label="YouTube">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center text-sm pt-8 border-t" style="border-color: rgba(248, 163, 138, 0.2); color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                <p>&copy; {{ date('Y') }} Fortune Compass. All rights reserved.</p>
            </div>
        </footer>

        @livewireScripts
        @fluxScripts
    </body>
</html>

