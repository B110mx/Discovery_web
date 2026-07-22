<title>@yield('title', $seo['title'] ?? __('site.seo.default_title'))</title>
<meta name="description" content="@yield('description', $seo['description'] ?? __('site.seo.default_description'))">
<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="@yield('title', $seo['title'] ?? __('site.seo.default_title'))">
<meta property="og:description" content="@yield('description', $seo['description'] ?? __('site.seo.default_description'))">
<meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:image" content="{{ $seo['image'] ?? asset('favicon.png') }}">
