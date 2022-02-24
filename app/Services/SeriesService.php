<?php

namespace App\Services;

use App\Helpers\MangaPassionApi;
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

        $apiSeries = MangaPassionApi::loadSeries($series->name);

        if (empty($apiSeries)) {
            throw new Exception('Manga Passion API returned no result');
        }

        $series->mangapassion_id = $apiSeries['mangapassion_id'];
        $series->name = $apiSeries['name'];
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

        return $series;
    }
}
