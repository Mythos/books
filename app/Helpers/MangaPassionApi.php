<?php

namespace App\Helpers;

use DateTime;
use Illuminate\Support\Facades\Http;
use Nicebooks\Isbn\Isbn;

class MangaPassionApi
{
    public const API_URL = 'https://api.manga-passion.de';

    public static function loadSeriesByTitle(string $title)
    {
        $response = Http::get(MangaPassionApi::API_URL . '/editions?order[titleLength]=asc&order[title]=asc&title=' . urlencode($title));
        if (!$response->successful()) {
            return null;
        }
        $response = $response->json();
        if (empty($response)) {
            return null;
        }
        $result = $response[0];
        $series = [];
        $series['mangapassion_id'] = $result['id'];

        return MangaPassionApi::loadSeriesById($result['id']);
    }

    public static function loadSeriesById(int $mangaPassionId)
    {
        $response = Http::get(MangaPassionApi::API_URL . '/editions/' . $mangaPassionId);
        if (!$response->successful()) {
            return null;
        }
        $result = $response->json();
        if (empty($result)) {
            return null;
        }
        $series = [];
        $series['mangapassion_id'] = $result['id'];

        $series['name'] = $result['title'];
        $series['description'] = $result['description'];
        if ($result['status'] == 1 || $result['status'] == 2) {
            $series['status'] = $result['status'];
        } else {
            $series['status'] = 0;
        }
        $series['image_url'] = $result['cover'];

        if ($result['status'] == 2) {
            $series['total'] = $result['numVolumes'];
        }
        if (!empty($result['sources'])) {
            $source = $result['sources'][0];
            if ($result['status'] != 2) {
                $series['total'] = $source['volumes'];
            }
            if (!empty($source['tags'])) {
                $tags = collect($source['tags']);
                $series['demographics'] = $tags->where('type', '=', '0')->pluck('name')->first();
                $series['genres'] = $tags->where('type', '=', '1')->pluck('name');
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
        $url = MangaPassionApi::API_URL . '/editions/' . $mangaPassionId . '/volumes?itemsPerPage=500&order[number]=asc';
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
        $volumesResponse = Http::get(MangaPassionApi::API_URL . '/editions/' . $mangaPassionId . '/volumes?itemsPerPage=1&order[number]=asc');
        if (!$volumesResponse->successful()) {
            return null;
        }
        $volumesResult = $volumesResponse->json();
        if (empty($volumesResult)) {
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
