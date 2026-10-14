<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        return ['name' => 'Test Lamp', 'description' => 'A useful lamp.', 'category_id' => Category::factory()->create()->id, 'price' => '299.99', 'quantity' => 8, 'is_active' => 1, 'image' => UploadedFile::fake()->image('lamp.png', 40, 40)];
    }

    public function test_admin_can_create_product_with_reencoded_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['is_admin' => true]);
        $data = $this->payload();
        $data['image_path'] = '../../untrusted.php';

        $this->actingAs($user)->post(route('admin.products.store'), $data)->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Test Lamp')->firstOrFail();
        $this->assertSame('299.99', $product->price);
        $this->assertSame(8, $product->quantity);
        $this->assertStringEndsWith('.webp', $product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
        $this->get(route('storefront.show', $product))->assertOk()->assertSee('Test Lamp');
    }

    public function test_admin_can_replace_image_and_delete_product(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/old.webp', 'old');
        $product = Product::factory()->create(['image_path' => 'products/old.webp']);
        $user = User::factory()->create(['is_admin' => true]);
        $data = $this->payload();

        $this->actingAs($user)->put(route('admin.products.update', $product), $data)->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame('Test Lamp', $product->name);
        Storage::disk('public')->assertMissing('products/old.webp');
        Storage::disk('public')->assertExists($product->image_path);
        $this->delete(route('admin.products.destroy', $product))->assertRedirect(route('admin.products.index'));
        $this->assertModelMissing($product);
        Storage::disk('public')->assertMissing($product->image_path);
    }

    public function test_invalid_product_is_not_saved(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['is_admin' => true]);
        $data = $this->payload();
        $data['price'] = '-10';
        $data['quantity'] = '-1';
        $data['category_id'] = 99999;
        $data['image'] = UploadedFile::fake()->create('attack.php', 1, 'application/x-httpd-php');

        $this->actingAs($user)->post(route('admin.products.store'), $data)
            ->assertSessionHasErrors(['price', 'quantity', 'category_id', 'image']);

        $this->assertDatabaseCount('products', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_non_admin_cannot_write_products(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $product = Product::factory()->create();

        $this->actingAs($user)->post(route('admin.products.store'), [])->assertForbidden();
        $this->put(route('admin.products.update', $product), [])->assertForbidden();
        $this->delete(route('admin.products.destroy', $product))->assertForbidden();

        $this->assertModelExists($product);
        $this->assertDatabaseCount('products', 1);
    }

    public function test_admin_forms_and_product_list_render(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create();

        $this->actingAs($user)->get(route('admin.products.index'))->assertOk()->assertSee($product->name);
        $this->get(route('admin.products.create'))->assertOk();
        $this->get(route('admin.products.edit', $product))->assertOk();
    }

    public function test_svg_and_oversized_uploads_are_rejected(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $data = $this->payload();
        $data['image'] = UploadedFile::fake()->createWithContent('image.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');

        $this->actingAs($user)->post(route('admin.products.store'), $data)->assertSessionHasErrors('image');
        $data['image'] = UploadedFile::fake()->image('big.png', 20, 20)->size(2049);
        $this->post(route('admin.products.store'), $data)->assertSessionHasErrors('image');
        $this->assertDatabaseCount('products', 0);
    }
}
