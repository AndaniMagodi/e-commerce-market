<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'quantity',
        'is_active',
        'image_path',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'quantity' => 'integer', 'is_active' => 'boolean'];
    }

    public function imageUrl(): string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : asset('images/product-placeholder.svg');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
