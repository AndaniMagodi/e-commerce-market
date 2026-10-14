@extends('layouts.admin')
@section('title', $product->exists ? 'Edit product' : 'Add product')
@section('section', 'Products')
@section('content')<a class="breadcrumb" href="{{ route('admin.products.index') }}">← Back to products</a>
<div class="page-heading">
<div>
<span class="eyebrow">CATALOGUE MANAGEMENT</span>
<h1>{{ $product->exists ? 'Edit product' : 'Add a new product' }}</h1>
<p class="muted">Give your product the details it needs to stand out.</p>
</div>
</div>@if($errors->any())<div class="notice error" role="alert">
<strong>Please check the highlighted fields.</strong>
<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="product-form">@csrf @if($product->exists) @method('PUT') 
@endif
<div class="form-main panel">
<h2>Product details</h2>
<div class="field">
<label for="name">Product name</label>
<input id="name" name="name" maxlength="200" value="{{ old('name', $product->name) }}" required>@error('name')<p class="field-error">{{ $message }}</p>
@enderror
</div>
<div class="field">
<label for="description">Description</label>
<textarea id="description" name="description" rows="5" maxlength="5000" required>{{ old('description', $product->description) }}</textarea>@error('description')<p class="field-error">{{ $message }}</p>
@enderror
</div>
<div class="field">
<label for="category_id">Category</label>
<select id="category_id" name="category_id" required>
<option value="">Choose a category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string)old('category_id', $product->category_id) === (string)$category->id)>{{ $category->name }}</option>
@endforeach
</select>@error('category_id')<p class="field-error">{{ $message }}</p>
@enderror
</div>
<div class="form-row">
<div class="field">
<label for="price">Price (ZAR)</label>
<input id="price" name="price" type="number" min="0" max="9999999999.99" step="0.01" value="{{ old('price', $product->price) }}" required>@error('price')<p class="field-error">{{ $message }}</p>
@enderror
</div>
<div class="field">
<label for="quantity">Stock quantity</label>
<input id="quantity" name="quantity" type="number" min="0" max="1000000" step="1" value="{{ old('quantity', $product->quantity) }}" required>@error('quantity')<p class="field-error">{{ $message }}</p>
@enderror
</div>
</div>
</div>
<aside class="form-side">
<section class="panel">
<h2>Product image</h2>
<div class="upload-preview">
<img id="image-preview" src="{{ $product->imageUrl() }}" alt="Product image preview">
</div>
<div class="field">
<label for="image">{{ $product->exists ? 'Replace image (optional)' : 'Upload image' }}</label>
<input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" data-image-preview="image-preview" @required(!$product->exists)>
<small>JPG, PNG or WebP. Maximum 2 MB and 3000 × 3000 pixels.</small>@error('image')<p class="field-error">{{ $message }}</p>
@enderror
</div>
</section>
<section class="panel">
<h2>Visibility</h2>
<input type="hidden" name="is_active" value="0">
<label class="checkbox-label">
<input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Publish in storefront</label>
<p class="muted">Hidden products remain available here. Zero stock is displayed as out of stock.</p>@error('is_active')<p class="field-error">{{ $message }}</p>
@enderror
</section>
</aside>
<div class="form-footer">
<a href="{{ route('admin.products.index') }}">Cancel</a>
<button class="button" type="submit">{{ $product->exists ? 'Save changes' : 'Create product' }} →</button>
</div>
</form>
@endsection
