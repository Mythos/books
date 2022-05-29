<?php

namespace App\Helpers;

use App\Models\Series;
use App\Models\Volume;
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

    public static function updateSeriesImage(Series $series, $wasCreated = false): void
    {
        if (!$wasCreated && !$series->isDirty('image_url') && !$series->wasChanged('image_url')) {
            return;
        }

        if (!empty($series->image_url)) {
            static::createAndSaveCoverImage($series->image_url, $series->image_path);

            return;
        }
        Storage::disk('public')->delete($series->image_path . '/cover.jpg');
        Storage::disk('public')->delete($series->image_path . '/cover_sfw.jpg');
        Storage::disk('public')->delete('thumbnails/' . $series->image_path . '/cover.jpg');
        Storage::disk('public')->delete('thumbnails/' . $series->image_path . '/cover_sfw.jpg');
    }

    public static function updateVolumeImage(Volume $volume, $wasCreated = false): void
    {
        if (!$wasCreated && !$volume->isDirty('image_url') && !$volume->wasChanged('image_url')) {
            return;
        }

        if (!empty($volume->image_url)) {
            static::createAndSaveCoverImage($volume->image_url, $volume->image_path);

            return;
        }
        Storage::disk('public')->delete($volume->image_path . '/cover.jpg');
        Storage::disk('public')->delete($volume->image_path . '/cover_sfw.jpg');
        Storage::disk('public')->deleteDirectory('thumbnails/' . $volume->image_path);
    }

    public static function createAndSaveCoverImage($url, $path): void
    {
        $image = ImageHelpers::getImage($url);
        if (!empty($image)) {
            ImageHelpers::storePublicImage($image, $path . '/cover.jpg', true);
            $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
            ImageHelpers::storePublicImage($nsfwImage, $path . '/cover_sfw.jpg', true);
        }
    }
}
