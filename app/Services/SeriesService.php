<?php

namespace App\Services;

use App\Helpers\ImageHelpers;
use App\Helpers\MangaPassionApi;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Volume;
use Exception;

class SeriesService
{
    public function refreshMetadata(Series $series)
    {
        if (empty($series->mangapassion_id)) {
            throw new Exception('Manga Passion ID not set');
        }

        $apiSeries = MangaPassionApi::loadSeriesById($series->mangapassion_id);

        if (empty($apiSeries)) {
            throw new Exception('Manga Passion API returned no result');
        }

        $series->mangapassion_id = $apiSeries['mangapassion_id'];
        $series->name = $apiSeries['name'];
        $series->description = $apiSeries['description'];
        $series->status = $apiSeries['status'];
        $series->total = $apiSeries['total'];
        $series->default_price = $apiSeries['default_price'];
        $series->image_url = $apiSeries['image_url'];

        $publisher = Publisher::whereName($apiSeries['publisher'])->first();
        if (!empty($publisher)) {
            $series->publisher_id = $publisher->id;
        } else {
            $publisher = new Publisher(['name' => $apiSeries['publisher']]);
            $publisher->save();

            $series->publisher_id = $publisher->id;
        }

        $genres = [];
        if (!empty($apiSeries['demographics'])) {
            $demographics = Genre::whereType(0)->firstOrCreate([
                'name' => $apiSeries['demographics'],
                'type' => 0,
            ]);
            $genres[] = $demographics->id;
        }
        if (!empty($apiSeries['genres'])) {
            foreach ($apiSeries['genres'] as $genreName) {
                $genre = Genre::whereType(1)->firstOrCreate([
                    'name' => $genreName,
                    'type' => 1,
                ]);
                $genres[] = $genre->id;
            }
        }
        $series->genres()->sync($genres);

        return $series;
    }

    public function updateVolumes(Series $series): array
    {
        $data = [];
        if (empty($series->mangapassion_id)) {
            return [];
        }
        $volumes = Volume::whereSeriesId($series->id)->get();

        $volumesResult = MangaPassionApi::loadVolumes($series->mangapassion_id);
        $newVolumes = [];

        foreach ($volumesResult as $volumeResult) {
            $number = $volumeResult['number'];
            $isbn = $volumeResult['isbn'];
            $publish_date = $volumeResult['publish_date'];
            $price = $volumeResult['price'];
            $image_url = $volumeResult['image_url'];

            $volume = null;
            if (!empty($isbn)) {
                $volume = $volumes->firstWhere('isbn', $isbn);
            }
            if (!empty($number)) {
                $volume = $volumes->firstWhere('number', $number);
            }
            if (empty($volume)) {
                $newVolumes[] = $volumeResult;
                continue;
            }

            if ($volume->status == 0 || ($volume->series->subscription_active && $volume->status == 1)) {
                $volume->price = $price;
            }

            $volume->number = $number;
            $volume->publish_date = !empty($publish_date) ? $publish_date->format('Y-m-d') : null;
            if (!empty($isbn)) {
                $volume->isbn = $isbn;
            }
            $volume->image_url = $image_url;
            $volume->save();
            $data[] = $volume;

            if (empty($image_url)) {
                continue;
            }
            $image = ImageHelpers::getImage($image_url);
            if (!empty($image)) {
                ImageHelpers::storePublicImage($image, $volume->image_path . '/cover.jpg');
                $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
                ImageHelpers::storePublicImage($nsfwImage, $volume->image_path . '/cover_sfw.jpg');
            }
        }

        foreach ($newVolumes as $newVolume) {
            $number = $newVolume['number'];
            $isbn = $newVolume['isbn'];
            $publish_date = $newVolume['publish_date'];
            $price = $newVolume['price'];
            $image_url = $volumeResult['image_url'];

            $volume = new Volume([
                'series_id' => $series->id,
                'isbn' => $isbn,
                'number' => $number,
                'publish_date' => !empty($publish_date) ? $publish_date->format('Y-m-d') : null,
                'price' => $price,
                'status' => $series->subscription_active,
                'image_url' => $image_url,
            ]);
            $volume->save();
            $data[] = $volume;

            if (empty($image_url)) {
                continue;
            }
            $image = ImageHelpers::getImage($image_url);
            if (!empty($image)) {
                ImageHelpers::storePublicImage($image, $volume->image_path . '/cover.jpg');
                $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
                ImageHelpers::storePublicImage($nsfwImage, $volume->image_path . '/cover_sfw.jpg');
            }
        }
        $this->resetNumbers($series->id);

        return $data;
    }

    public function resetNumbers(int $seriesId): void
    {
        $volumes = Volume::whereSeriesId($seriesId)->orderBy('number')->get();
        $number = 1;
        foreach ($volumes as $volume) {
            $volume->number = $number;
            $volume->save();
            $number++;
        }
    }
}
