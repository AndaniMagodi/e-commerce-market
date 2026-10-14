<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductImageStorage
{
    public function store(UploadedFile $file): string
    {
        $image = @imagecreatefromstring($file->get());
        if ($image === false) {
            throw ValidationException::withMessages(['image' => 'This image could not be decoded. Choose a valid JPG, PNG or WebP.']);
        }
        ob_start();
        try {
            $encoded = imagewebp($image, null, 85);
            $contents = ob_get_contents();
        } finally {
            ob_end_clean();
            imagedestroy($image);
        }
        if (! $encoded || ! $contents) {
            throw new \RuntimeException('Image encoding failed.');
        }
        $path = 'products/'.Str::uuid().'.webp';
        if (! Storage::disk('public')->put($path, $contents)) {
            throw new \RuntimeException('Image storage failed.');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path && str_starts_with($path, 'products/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
