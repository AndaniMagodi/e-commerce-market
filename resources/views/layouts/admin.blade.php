<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Overview') | E-Commerce Market Admin</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer>
</script>
</head>
<body class="admin-body">
<a class="skip-link" href="#main">Skip to content</a>
<div class="admin-shell">
<aside class="sidebar">
<x-brand/>
<p class="sidebar-label">STORE MANAGEMENT</p>
<nav aria-label="Admin navigation">
<a class="{{ request()->routeIs('admin.dashboard') ? 'selected' : '' }}" href="{{ route('admin.dashboard') }}">
<span>◫</span> Overview</a>
<a class="{{ request()->routeIs('admin.products.*') ? 'selected' : '' }}" href="{{ route('admin.products.index') }}">
<span>▦</span> Products</a>
<a href="{{ route('storefront.index') }}">
<span>↗</span> View storefront</a>
</nav>
<div class="sidebar-bottom">
<div class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
<strong>{{ auth()->user()->name }}</strong>
<small>Administrator</small>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-button" type="submit">Log out →</button>
</form>
</div>
</aside>
<div class="admin-workspace">
<header class="admin-topbar">
<span>Workspace <span class="muted">/ @yield('section', 'Overview')</span>
</span>
<span class="status-dot">Store connected</span>
<form class="mobile-logout" method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Log out →</button>
</form>
</header>
<main id="main" class="admin-content">@if(session('success'))<div class="notice success" role="status">{{ session('success') }}</div>
@endif
 @yield('content')</main>
</div>
</div>
</body>
</html>