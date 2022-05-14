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

        return FacadesImage::make($url)->resize(null, 400, function ($constraint): void {
            $constraint->aspectRatio();
        })->encode('jpg');
    }

    public static function storePublicImage($image, $path, $generateThumbnail): void
    {
        if (empty($image)) {
            return;
        }
        Storage::disk('public')->put($path, $image);
        if (!$generateThumbnail) {
            return;
        }
        $thumbnail = FacadesImage::make(clone $image)->resize(null, 50, function ($constraint): void {
            $constraint->aspectRatio();
        })->encode('jpg');
        Storage::disk('public')->put('thumbnails/' . $path, $thumbnail);
    }
}
