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
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "{{ $pageOgType === 'article' ? 'Article' : 'WebSite' }}",
            "name": "Fortune Compass",
            "url": "{{ url()->current() }}",
            "headline": "{{ $pageOgTitle }}",
            "description": "{{ $pageOgDescription }}",
            @if($pageOgType === 'article')
            "author": {
                "@type": "Person",
                "name": "Fortune Compass"
            },
            "publisher": {
                "@type": "Organization",
                "name": "Fortune Compass"
            },
            @endif
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ url('/') }}?s={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }
        </script>
    </head>
    <body class="antialiased" style="background-color: #FFFDF9; color: #2A2E47; font-family: 'Noto Sans JP', 'Inter', sans-serif;">
        <header class="w-full max-w-7xl mx-auto px-4 py-6">
            <nav class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-xl font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">
                    Fortune Compass
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif;">ダッシュボード</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm hover:text-[#F8A38A] transition-colors" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif;">ログイン</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm px-4 py-2 rounded-lg hover:bg-[#F8A38A] transition-colors" style="background-color: #2A2E47; color: #FFFDF9; font-family: 'Noto Sans JP', sans-serif;">
                                無料登録
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="w-full max-w-7xl mx-auto px-4 py-12 mt-20 border-t" style="border-color: rgba(248, 163, 138, 0.2); background-color: #FFFDF9;">
            <div class="text-center text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                <p>&copy; {{ date('Y') }} Fortune Compass. All rights reserved.</p>
            </div>
        </footer>

        @livewireScripts
        @fluxScripts
    </body>
</html>

