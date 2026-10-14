<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalog = [
            'Tools' => [
                ['Cordless Drill', 'Rechargeable drill for everyday tasks.', '1299.00', 12],
                ['Tool Kit', 'Hand tools in a portable case.', '799.00', 8],
            ],
            'Electronics' => [
                ['Bluetooth Speaker', 'Portable wireless speaker.', '499.00', 15],
                ['Wireless Headphones', 'Over-ear wireless headphones.', '899.00', 0],
            ],
            'Home & Kitchen' => [
                ['Electric Kettle', 'Stainless steel electric kettle.', '349.00', 20],
                ['Air Fryer', 'Compact countertop air fryer.', '1599.00', 6],
            ],
            'Office' => [
                ['Office Chair', 'Adjustable chair for your workspace.', '1899.00', 5],
                ['Desk Lamp', 'LED lamp with adjustable brightness.', '299.00', 0],
            ],
        ];

        foreach ($catalog as $categoryName => $products) {
            $category = Category::firstOrCreate([
                'name' => $categoryName,
            ]);

            foreach ($products as [$name, $description, $price, $quantity]) {
                $slug = Str::slug($name);
                $imagePath = 'demo/'.$slug.'.jpg';
                Storage::disk('public')->put($imagePath, file_get_contents(public_path('images/photos/'.$slug.'.jpg')));
                $product = Product::firstOrCreate(
                    ['name' => $name],
                    [
                        'category_id' => $category->id,
                        'description' => $description,
                        'price' => $price,
                        'quantity' => $quantity,
                        'is_active' => true,
                        'image_path' => $imagePath,
                    ]
                );
                if ($product->image_path === null || $product->image_path === 'demo/'.$slug.'.svg') {
                    $product->update(['image_path' => $imagePath]);
                }
            }
        }
    }
}
