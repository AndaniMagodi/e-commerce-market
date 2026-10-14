<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_and_category_filter_exclude_unpublished_products(): void
    {
        $visible = Product::factory()->create(['name' => 'Cordless Drill']);
        Product::factory()->create(['name' => 'Hidden Drill', 'is_active' => false]);
        Product::factory()->create(['name' => 'Kettle']);

        $this->get('/?search=Drill&category='.$visible->category_id)
            ->assertOk()->assertSee('Cordless Drill')->assertDontSee('Hidden Drill')->assertDontSee('Kettle');
    }

    public function test_product_details_escape_text_and_show_out_of_stock(): void
    {
        $product = Product::factory()->create(['name' => '<script>alert(1)</script>', 'description' => '<img src=x onerror=alert(1)>', 'quantity' => 0]);

        $this->get(route('storefront.show', $product))->assertOk()
            ->assertSee($product->name)->assertSee($product->description)
            ->assertDontSee($product->name, false)->assertDontSee($product->description, false)
            ->assertSee('Out of stock');
    }

    public function test_hidden_and_missing_products_return_not_found(): void
    {
        $product = Product::factory()->create(['is_active' => false]);

        $this->get(route('storefront.show', $product))->assertNotFound();
        $this->get('/products/99999')->assertNotFound();
    }
}
