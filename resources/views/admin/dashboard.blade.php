@extends('layouts.admin')
@section('title', 'Overview')
@section('content')<div class="page-heading">
<div>
<span class="eyebrow">YOUR STORE AT A GLANCE</span>
<h1>Overview</h1>
<p class="muted">Welcome, {{ auth()->user()->name }}. Here’s how your catalogue looks.</p>
</div>
<a class="button" href="{{ route('admin.products.create') }}">+ Add product</a>
</div>
<div class="stats-grid">
<article class="stat-card">
<span>Total products</span>
<strong>{{ $totalProducts }}</strong>
<small>Across your catalogue</small>
</article>
<article class="stat-card">
<span>Active products</span>
<strong>{{ $activeProducts }}</strong>
<small>Published in the storefront</small>
</article>
<article class="stat-card">
<span>Out-of-stock products</span>
<strong>{{ $outOfStockProducts }}</strong>
<small>Products with zero quantity</small>
</article>
<article class="stat-card">
<span>Total categories</span>
<strong>{{ $totalCategories }}</strong>
<small>Ways to browse your store</small>
</article>
</div>
<div class="dashboard-panels">
<section class="panel">
<div class="panel-heading">
<h2>Keep your catalogue moving</h2>
<span>↗</span>
</div>
<p>Manage products, update stock and publish new finds. Your saved changes appear in the storefront on its next page load.</p>
<a class="button secondary" href="{{ route('admin.products.index') }}">Manage products →</a>
</section>
<section class="panel green-panel">
<span class="eyebrow">FROM WORKSPACE TO STOREFRONT</span>
<h2>See it through<br>your customer’s eyes.</h2>
<a href="{{ route('storefront.index') }}">View shop ↗</a>
</section>
</div>
@endsection
