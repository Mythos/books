<?php

namespace App\Services;

use App\Helpers\MangaPassionApi;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
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
}
