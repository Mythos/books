<?php

namespace App\Helpers;

use App\Models\Publisher;
use App\Models\Series;
use App\Models\Volume;
use Exception;
use Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\EncodedImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class ImageHelpers
{
    private const THUMBNAIL_FOLDER = 'thumbnails/';

    private const COVER_FILENAME = 'cover';

    private const COVER_SFW_FILENAME = 'cover_sfw';

    private const LOGO_FILENAME = 'logo';

    private const LOGO_SFW_FILENAME = 'logo_sfw';

    private const IMAGE_FILENAME = 'image';

    private const IMAGE_SFW_FILENAME = 'image_sfw';

    public static function getImage($url, $outputType = null): ?EncodedImage
    {
        $outputType = config('images.type');
        $image = static::downloadImage($url);
        if (empty($image)) {
            return null;
        }

        return static::output($image, $outputType);
    }

    public static function storePublicImage(ImageInterface $image, $path, $generateThumbnail): void
    {
        if (empty($image)) {
            return;
        }
        $encodedImage = static::output($image, config('images.type'));
        Storage::disk('public')->put($path, $encodedImage);
        if (!$generateThumbnail) {
            return;
        }
        $manager = new ImageManager(new Driver());
        $thumbnail = $manager->read(clone $image)->scaleDown(width: 200);
        $thumbnail = static::output($thumbnail, config('images.type'));
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
        $coverFilename = static::COVER_FILENAME . '.' . config('images.type');
        $coverSfwFilename = static::COVER_SFW_FILENAME . '.' . config('images.type');
        Storage::disk('public')->delete([
            implode(DIRECTORY_SEPARATOR, [$path, $coverFilename]),
            implode(DIRECTORY_SEPARATOR, [$path, $coverSfwFilename]),
            implode(DIRECTORY_SEPARATOR, [$thumbnailPath, $coverFilename]),
            implode(DIRECTORY_SEPARATOR, [$thumbnailPath, $coverSfwFilename]),
        ]);
    }

    public static function updatePublisherImage(Publisher $publisher, $wasCreated = false): void
    {
        if (!$wasCreated && !$publisher->isDirty('image_url') && !$publisher->wasChanged('image_url')) {
            return;
        }

        if (!empty($publisher->image_url)) {
            static::createAndSaveCoverImage($publisher->image_url, $publisher->image_path);

            return;
        }

        $path = $publisher->image_path;
        $thumbnailPath = static::THUMBNAIL_FOLDER . $publisher->image_path;
        $coverFilename = static::LOGO_FILENAME . '.' . config('images.type');
        $coverSfwFilename = static::LOGO_SFW_FILENAME . '.' . config('images.type');
        Storage::disk('public')->delete([
            implode(DIRECTORY_SEPARATOR, [$path, $coverFilename]),
            implode(DIRECTORY_SEPARATOR, [$path, $coverSfwFilename]),
            implode(DIRECTORY_SEPARATOR, [$thumbnailPath, $coverFilename]),
            implode(DIRECTORY_SEPARATOR, [$thumbnailPath, $coverSfwFilename]),
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
        $coverFilename = static::COVER_FILENAME . '.' . config('images.type');
        $coverSfwFilename = static::COVER_SFW_FILENAME . '.' . config('images.type');
        Storage::disk('public')->delete([
            implode(DIRECTORY_SEPARATOR, [$path, $coverFilename]),
            implode(DIRECTORY_SEPARATOR, [$path, $coverSfwFilename]),
        ]);
        Storage::disk('public')->deleteDirectory($thumbnailPath);
    }

    public static function createAndSaveCoverImage($url, $path): void
    {
        $image = ImageHelpers::downloadImage($url);
        if (empty($image)) {
            return;
        }
        $coverFilename = static::COVER_FILENAME . '.' . config('images.type');
        $coverSfwFilename = static::COVER_SFW_FILENAME . '.' . config('images.type');
        ImageHelpers::storePublicImage($image, implode(DIRECTORY_SEPARATOR, [$path, $coverFilename]), true);

        $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5));
        ImageHelpers::storePublicImage($nsfwImage, implode(DIRECTORY_SEPARATOR, [$path, $coverSfwFilename]), true);
    }

    public static function createAndSaveArticleImage($url, $path): void
    {
        $image = ImageHelpers::downloadImage($url);
        if (empty($image)) {
            return;
        }
        ImageHelpers::storePublicImage($image, $path . '/image.' . config('images.type'), false);
    }

    private static function downloadImage($url): ?ImageInterface
    {
        if (empty($url)) {
            return null;
        }
        try {
            $manager = new ImageManager(new Driver());
            $response = Http::get($url);
            if (!$response->successful()) {
                return null;
            }
            $image = $response->body();
            $image = $manager->read($image);
            $image = $image->scaleDown(width: 400);

            return $image;
        } catch (Exception $exception) {
            dd($exception);
            Log::warning('Could not fetch image from ' . $url, ['exception' => json_encode($exception)]);

            return null;
        }
    }

    private static function output(ImageInterface $image, $outputType) : EncodedImage
    {
        if ($outputType == 'png') {
            return $image->toPng();
        } elseif ($outputType == 'webp') {
            return $image->toWebp();
        }

        return $image->toJpeg();
    }
}
