<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Fortune Compass - 迷ったとき、自分に戻れる光を届ける' }}</title>
<meta name="description" content="{{ $description ?? 'Fortune Compassは、四柱推命・紫微斗数・数秘術・タロットの4つの占術を通じて、あなた自身を深く知り、毎日の羅針盤として活用できるサービスです。' }}" />
<meta name="keywords" content="{{ $keywords ?? '占い,四柱推命,紫微斗数,数秘術,タロット,運勢,Fortune Compass' }}" />
<meta name="author" content="Fortune Compass" />
<meta name="robots" content="{{ $robots ?? 'index, follow' }}" />

<!-- OGP Tags -->
<meta property="og:type" content="{{ $ogType ?? 'website' }}" />
<meta property="og:title" content="{{ $ogTitle ?? $title ?? 'Fortune Compass - 迷ったとき、自分に戻れる光を届ける' }}" />
<meta property="og:description" content="{{ $ogDescription ?? $description ?? 'Fortune Compassは、四柱推命・紫微斗数・数秘術・タロットの4つの占術を通じて、あなた自身を深く知り、毎日の羅針盤として活用できるサービスです。' }}" />
<meta property="og:url" content="{{ $ogUrl ?? url()->current() }}" />
<meta property="og:site_name" content="Fortune Compass" />
@if(isset($ogImage))
<meta property="og:image" content="{{ $ogImage }}" />
@else
<meta property="og:image" content="{{ asset('images/og-image.jpg') }}" />
@endif
<meta property="og:locale" content="ja_JP" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $ogTitle ?? $title ?? 'Fortune Compass - 迷ったとき、自分に戻れる光を届ける' }}" />
<meta name="twitter:description" content="{{ $ogDescription ?? $description ?? 'Fortune Compassは、四柱推命・紫微斗数・数秘術・タロットの4つの占術を通じて、あなた自身を深く知り、毎日の羅針盤として活用できるサービスです。' }}" />
@if(isset($ogImage))
<meta name="twitter:image" content="{{ $ogImage }}" />
@else
<meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}" />
@endif

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<!-- Fortune Compass ブランドフォント -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
