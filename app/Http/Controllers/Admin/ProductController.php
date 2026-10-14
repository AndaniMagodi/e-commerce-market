<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,inactive,out-of-stock'],
        ]);
        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? '';
        $query = Product::with('category');
        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }
        if ($status === 'out-of-stock') {
            $query->where('quantity', 0);
        } elseif ($status !== '') {
            $query->where('is_active', $status === 'active');
        }

        return view('admin.products.index', [
            'products' => $query->latest('id')->paginate(10)->withQueryString(),
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(['is_active' => true, 'quantity' => 0]), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(ProductRequest $request, ProductImageStorage $images): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['image_path'] = $images->store($request->file('image'));
        try {
            Product::create($data);
        } catch (Throwable $exception) {
            $images->delete($data['image_path']);
            throw $exception;
        }

        return redirect()->route('admin.products.index')->with('success', 'Product added. Published products are now visible in the shop.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(ProductRequest $request, Product $product, ProductImageStorage $images): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $oldPath = $product->image_path;
        $newPath = null;
        if ($request->hasFile('image')) {
            $newPath = $images->store($request->file('image'));
            $data['image_path'] = $newPath;
        }
        try {
            $product->update($data);
        } catch (Throwable $exception) {
            $images->delete($newPath);
            throw $exception;
        }
        if ($newPath) {
            $images->delete($oldPath);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated. The shop uses the latest saved details.');
    }

    public function destroy(Product $product, ProductImageStorage $images): RedirectResponse
    {
        $path = $product->image_path;
        $product->delete();
        $images->delete($path);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
