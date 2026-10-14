@extends('layouts.shop')
@section('title', $product->name.' | E-Commerce Market')
@section('content')<div class="container detail-container">
<a class="breadcrumb" href="{{ route('storefront.index') }}#collection">← Back to products</a>
<div class="product-detail">
<div class="detail-image">
<img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
</div>
<div class="detail-copy">
<span class="eyebrow">{{ $product->category->name }}</span>
<h1>{{ $product->name }}</h1>
<span class="badge {{ $product->quantity > 0 ? 'stock' : 'unavailable' }}">{{ $product->quantity > 0 ? 'In stock' : 'Out of stock' }}</span>
<p class="detail-price">R{{ number_format($product->price, 2) }}</p>
<p class="description">{{ $product->description }}</p>
<dl class="spec-list">
<div>
<dt>Category</dt>
<dd>{{ $product->category->name }}</dd>
</div>
<div>
<dt>Availability</dt>
<dd>{{ $product->quantity > 0 ? $product->quantity.' available' : 'Currently out of stock' }}</dd>
</div>
<div>
<dt>Product reference</dt>
<dd>AF-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</dd>
</div>
</dl>
<a class="button" href="{{ route('storefront.index', ['category' => $product->category_id]) }}#collection">Explore this category →</a>
</div>
</div>
</div>
@endsection
