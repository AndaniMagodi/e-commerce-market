<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#173e36">
<title>@yield('title', 'Afrimart | Everyday essentials')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer>
</script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<div class="announcement">A little upgrade. A lot of possibility. <span>Discover everyday essentials.</span>
</div>
<header class="shop-header">
<div class="container header-row">
<x-brand/>
<form class="search-box" method="GET" action="{{ route('storefront.index') }}">
<label class="sr-only" for="header-search">Search products</label>
<input id="header-search" name="search" type="search" maxlength="100" value="{{ request('search') }}" placeholder="What are you looking for?">
<button aria-label="Search products" type="submit">⌕</button>
</form>
<a class="admin-link" href="{{ route('admin.dashboard') }}">Admin portal <span aria-hidden="true">↗</span>
</a>
</div>
<nav class="container shop-nav" aria-label="Main navigation">
<a href="{{ route('storefront.index') }}">Shop all</a>
<a href="{{ route('storefront.index') }}#categories">Categories</a>
<a href="{{ route('storefront.index') }}#collection">Explore the collection</a>
<span>Thoughtfully selected. Simply useful.</span>
</nav>
</header>
<main id="main">@yield('content')</main>
<footer class="shop-footer">
<div class="container footer-row">
<x-brand/>
<p>Everyday essentials. New possibilities.</p>
<span>Assessment project · Andani Magodi</span>
</div>
</footer>
</body>
</html>