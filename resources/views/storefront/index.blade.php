@extends('layouts.shop')
@section('content')
<section class="hero container">
<div class="hero-copy">
<span class="eyebrow">GOOD FINDS. EVERY DAY.</span>
<h1>Small upgrades.<br>
<em>Big possibilities.</em>
</h1>
<p>From your workspace to your kitchen, discover useful things that make everyday living a little better.</p>
<a class="button" href="#collection">Explore the collection <span>↗</span>
</a>
<div class="hero-note">
<span>✦</span> For your home, work and everything in between.</div>
</div>
<div class="hero-art">
<div class="art-circle">
</div>
<img src="{{ asset('images/photos/hero.jpg') }}" alt="Photograph of a desk with a lamp, headphones and a laptop">
<span class="art-label label-top">THE EVERYDAY EDIT</span>
<span class="art-label label-bottom">Useful by design.</span>
</div>
</section>
<section class="container categories-section" id="categories">
<div class="section-heading">
<div>
<span class="eyebrow">FIND YOUR NEXT ESSENTIAL</span>
<h2>Shop by category</h2>
</div>
<a href="{{ route('storefront.index') }}#collection">Browse all →</a>
</div>
<div class="category-grid">@foreach($categories as $category)<a class="category-tile {{ (string)$selectedCategory === (string)$category->id ? 'chosen' : '' }}" href="{{ route('storefront.index', ['category' => $category->id]) }}#collection">
<span class="category-number">0{{ $loop->iteration }}</span>
<strong>{{ $category->name }}</strong>
<span aria-hidden="true">↗</span>
</a>
@endforeach
</div>
</section>
<section class="container collection-section" id="collection">
<div class="section-heading">
<div>
<span class="eyebrow">CHOSEN FOR EVERYDAY LIFE</span>
<h2>{{ $search !== '' ? 'Search results' : 'The collection' }}</h2>
<p class="muted">{{ $products->total() }} products{{ $search !== '' ? ' matching “'.$search.'”' : ' to explore' }}</p>
</div>@if($search !== '' || $selectedCategory)<a class="text-link" href="{{ route('storefront.index') }}#collection">Clear filters ×</a>@else<span class="collection-note">A few good things go a long way.</span>
@endif
</div>@error('search')<p class="field-error">{{ $message }}</p>
@enderror

<div class="product-grid">@forelse($products as $product)<article class="product-card">
<a class="product-image" href="{{ route('storefront.show', $product) }}">
<img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy">
<span class="badge {{ $product->quantity > 0 ? 'stock' : 'unavailable' }}">{{ $product->quantity > 0 ? 'In stock' : 'Out of stock' }}</span>
<span class="card-arrow" aria-hidden="true">↗</span>
</a>
<div class="product-copy">
<span class="eyebrow">{{ $product->category->name }}</span>
<h3>
<a href="{{ route('storefront.show', $product) }}">{{ $product->name }}</a>
</h3>
<p>{{ $product->description }}</p>
<strong class="price">R{{ number_format($product->price, 2) }}</strong>
</div>
</article>
@empty
<div class="empty-state">
<h3>No products available.</h3>
<p>Try another search or browse the full collection.</p>
<a class="button" href="{{ route('storefront.index') }}">Browse products</a>
</div>
@endforelse
</div>
@if($products->hasPages())<nav class="pagination" aria-label="Product pages">@if($products->previousPageUrl())<a href="{{ $products->previousPageUrl() }}">← Previous</a>
@endif
<span>Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>@if($products->nextPageUrl())<a href="{{ $products->nextPageUrl() }}">Next →</a>
@endif
</nav>
@endif
</section>
<section class="container closing-banner">
<span class="eyebrow">MAKE ROOM FOR SOMETHING GOOD</span>
<h2>Your next everyday essential<br>might be right here.</h2>
<a href="#collection">Take another look ↗</a>
</section>

@endsection
