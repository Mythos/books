<?php

namespace App\Helpers;

use App\Constants\SeriesStatus;
use DateTime;
use Illuminate\Support\Facades\Http;
use Nicebooks\Isbn\Isbn;

class MangaPassionApi
{
    private const API_URL = 'https://api.manga-passion.de';

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
        $response = self::request($mangaPassionId);
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
        $series['description'] = $result['description'] ?? '';
        $series['status'] = $result['status'] ?? SeriesStatus::ANNOUNCED;
        $series['image_url'] = $result['cover'] ?? '';
        $series['total'] = $result['numVolumes'] ?? 0;
        if (!empty($result['sources'])) {
            $source = $result['sources'][0];
            $series['total'] = $source['volumes'] ?? null;
            $series['source_status'] = $source['status'] ?? null;
            $series['source_name'] = $source['title'] ?? $source['romaji'] ?? null;
            $series['source_name_romaji'] = $source['romaji'] ?? null;
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

    public static function loadVolumes($mangaPassionId, $total)
    {
        $result = [];
        $response = self::request($mangaPassionId, 'volumes?itemsPerPage=' . $total . '&order[number]=asc');
        if ($response->successful()) {
            $responseBody = $response->json();

            foreach ($responseBody as $responseItem) {
                $number = !empty($responseItem['number']) ? $responseItem['number'] : 1;
                $publish_date = null;
                if ($responseItem['status'] < 2) {
                    $publish_date = !empty($responseItem['date']) ? new DateTime($responseItem['date']) : null;
                }
                $isbn = !empty($responseItem['isbn13']) ? (string) Isbn::of($responseItem['isbn13'])->to13() : null;
                $price = !empty($responseItem['price']) ? floatval($responseItem['price']) / 100.0 : 0.00;

                $result[] = [
                    'number' => $number,
                    'isbn' => $isbn,
                    'publish_date' => $publish_date,
                    'price' => $price,
                    'image_url' => $responseItem['cover'],
                ];
            }

            return collect($result);
        }

        return null;
    }

    public static function getDefaultPrice($mangaPassionId): ?float
    {
        $volumesResponse = self::request($mangaPassionId, 'volumes?itemsPerPage=1&order[number]=asc');
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

            return !empty($volumeResult['price']) ? floatval($volumeResult['price']) / 100.0 : 0.00;
        }

        return null;
    }

    private static function request($mangaPassionId, $additionalParameters = null)
    {
        $url = MangaPassionApi::API_URL . '/editions/' . $mangaPassionId;
        if (!empty($additionalParameters)) {
            $url .= '/' . $additionalParameters;
        }

        return Http::get($url);
    }
}
