<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as FacadesImage;
use Intervention\Image\Image;

class ImageHelpers
{
    public static function getImage($url): ?Image
    {
        if (empty($url)) {
            return null;
        }
        $image = FacadesImage::make($url)->resize(null, 400, function ($constraint): void {
            $constraint->aspectRatio();
        })->encode('jpg');

        return $image;
    }

    public static function storePublicImage($image, $path): void
    {
        if (empty($image)) {
            return;
        }
        Storage::disk('public')->put($path, $image);
    }
}
