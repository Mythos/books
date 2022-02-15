<?php

namespace App\Helpers;

use DateTime;
use Illuminate\Support\Facades\Http;
use Nicebooks\Isbn\Isbn;

class MangaPassionApi
{
    public static function loadSeries($title)
    {
        $response = Http::get('https://api.manga-passion.de/editions?order[titleLength]=asc&order[title]=asc&title=' . urlencode($title));
        if (!$response->successful()) {
            return null;
        }
        $response = $response->json();
        if (count($response) == 0) {
            return null;
        }
        $result = $response[0];
        $series = [];
        $series['mangapassion_id'] = $result['id'];

        $series['name'] = $result['title'];
        if ($result['status'] == 1 || $result['status'] == 2) {
            $series['status'] = $result['status'];
        } else {
            $series['status'] = 0;
        }
        $series['image_url'] = $result['cover'];

        if ($result['status'] == 2) {
            $series['total'] = $result['numVolumes'];
        } elseif (!empty($result['sources'])) {
            $sourceId = $result['sources'][0]['id'];
            $sourceResponse = Http::get('https://api.manga-passion.de/sources/' . $sourceId);
            if ($sourceResponse->successful()) {
                $source = $sourceResponse->json();
                if (!empty($source)) {
                    if (!empty($source['volumes'])) {
                        $series['total'] = $source['volumes'];
                    }
                }
            }
        }

        $defaultPrice = MangaPassionApi::getDefaultPrice($series['mangapassion_id']);
        $series['default_price'] = $defaultPrice;

        if (!empty($result['publishers'])) {
            $series['publisher'] = $result['publishers'][0]['name'];
        }

        return $series;
    }

    public static function loadVolumes($mangaPassionId)
    {
        $result = [];
        $url = 'https://api.manga-passion.de/editions/' . $mangaPassionId . '/volumes?itemsPerPage=500&order[number]=asc';
        $response = Http::get($url);
        if ($response->successful()) {
            $responseBody = $response->json();

            foreach ($responseBody as $responseItem) {
                $number = !empty($responseItem['number']) ? $responseItem['number'] : 1;
                $publish_date = null;
                if ($responseItem['status'] < 2) {
                    $publish_date = !empty($responseItem['date']) ? new DateTime($responseItem['date']) : null;
                }
                $isbn = !empty($responseItem['isbn13']) ? (string) Isbn::of($responseItem['isbn13'])->to13() : null;
                $price = !empty($responseItem['price']) ? floatval($responseItem['price']) / 100.0 : 0;

                $result[] = [
                    'number' => $number,
                    'isbn' => $isbn,
                    'publish_date' => $publish_date,
                    'price' => $price,
                ];
            }

            return collect($result);
        }

        return null;
    }

    public static function getDefaultPrice($mangaPassionId): ?float
    {
        $volumesResponse = Http::get('https://api.manga-passion.de/editions/' . $mangaPassionId . '/volumes?itemsPerPage=1&order[number]=asc');
        if (!$volumesResponse->successful()) {
            return null;
        }
        $volumesResult = $volumesResponse->json();
        if (empty($volumeResult)) {
            return null;
        }
        foreach ($volumesResult as $volumeResult) {
            if (empty($volumeResult['price'])) {
                continue;
            }

            return !empty($volumeResult['price']) ? floatval($volumeResult['price']) / 100.0 : 0;
        }
    }
}
