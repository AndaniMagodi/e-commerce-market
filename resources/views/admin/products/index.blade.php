@extends('layouts.admin')
@section('title', 'Products')
@section('section', 'Products')
@section('content')<div class="page-heading">
<div>
<span class="eyebrow">CATALOGUE MANAGEMENT</span>
<h1>Products</h1>
<p class="muted">{{ $products->total() }} products · Manage prices, stock and visibility.</p>
</div>
<a class="button" href="{{ route('admin.products.create') }}">+ Add product</a>
</div>
<section class="panel table-panel">
<form class="filter-bar" method="GET" action="{{ route('admin.products.index') }}">
<label class="sr-only" for="product-search">Search products</label>
<input id="product-search" name="search" maxlength="100" value="{{ $search }}" placeholder="Search product names…">
<label class="sr-only" for="status">Publication or stock filter</label>
<select id="status" name="status">
<option value="">All products</option>
<option value="active" @selected($status === 'active')>Published</option>
<option value="inactive" @selected($status === 'inactive')>Hidden</option>
<option value="out-of-stock" @selected($status === 'out-of-stock')>Out of stock</option>
</select>
<button class="button secondary">Filter</button>
<a href="{{ route('admin.products.index') }}">Reset</a>
</form>@if($errors->any())<div class="notice error">{{ $errors->first() }}</div>
@endif
<div class="table-scroll">
<table>
<thead>
<tr>
<th>Product</th>
<th>Category</th>
<th>Price</th>
<th>Stock</th>
<th>Visibility</th>
<th>Actions</th>
</tr>
</thead>
<tbody>@forelse($products as $product)<tr>
<td>
<div class="table-product">
<img src="{{ $product->imageUrl() }}" alt="">
<div>
<strong>{{ $product->name }}</strong>
<small>AF-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</small>
</div>
</div>
</td>
<td>{{ $product->category->name }}</td>
<td class="nowrap">R{{ number_format($product->price, 2) }}</td>
<td>
<span class="badge {{ $product->quantity > 0 ? 'stock' : 'unavailable' }}">{{ $product->quantity }}</span>
</td>
<td>
<span class="badge {{ $product->is_active ? 'stock' : 'neutral' }}">{{ $product->is_active ? 'Published' : 'Hidden' }}</span>
</td>
<td>
<div class="table-actions">
<a href="{{ route('admin.products.edit', $product) }}">Edit</a>
<form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Delete this product permanently?">@csrf @method('DELETE')<button class="danger-link" type="submit">Delete</button>
</form>
</div>
</td>
</tr>
@empty
<tr>
<td colspan="6">
<div class="empty-state">
<h3>No matching products</h3>
<p>Clear your filters or add a new product.</p>
</div>
</td>
</tr>
@endforelse
</tbody>
</table>
</div>@if($products->hasPages())<nav class="pagination" aria-label="Product pages">@if($products->previousPageUrl())<a href="{{ $products->previousPageUrl() }}">← Previous</a>
@endif
<span>Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>@if($products->nextPageUrl())<a href="{{ $products->nextPageUrl() }}">Next →</a>
@endif
</nav>
@endif
</section>
@endsection
