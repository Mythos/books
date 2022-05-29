<?php

namespace App\Helpers;

use App\Models\Series;
use App\Models\Volume;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as FacadesImage;
use Intervention\Image\Image;

class ImageHelpers
{
    private const THUMBNAIL_FOLDER = 'thumbnails/';

    private const COVER_FILENAME = '/cover.jpg';

    private const COVER_SFW_FILENAME = '/cover_sfw.jpg';

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
        Storage::disk('public')->put(static::THUMBNAIL_FOLDER . $path, $thumbnail);
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

        $path = $series->image_path;
        $thumbnailPath = static::THUMBNAIL_FOLDER . $series->image_path;
        Storage::disk('public')->delete([
            $path . static::COVER_FILENAME,
            $path . static::COVER_SFW_FILENAME,
            $thumbnailPath . static::COVER_FILENAME,
            $thumbnailPath . static::COVER_SFW_FILENAME,
        ]);
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
        $path = $volume->image_path;
        $thumbnailPath = static::THUMBNAIL_FOLDER . $volume->image_path;
        Storage::disk('public')->delete([
            $path . static::COVER_FILENAME,
            $path . static::COVER_SFW_FILENAME,
        ]);
        Storage::disk('public')->deleteDirectory($thumbnailPath);
    }

    public static function createAndSaveCoverImage($url, $path): void
    {
        $image = ImageHelpers::getImage($url);
        if (!empty($image)) {
            ImageHelpers::storePublicImage($image, $path . static::COVER_FILENAME, true);
            $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
            ImageHelpers::storePublicImage($nsfwImage, $path . static::COVER_SFW_FILENAME, true);
        }
    }
}
