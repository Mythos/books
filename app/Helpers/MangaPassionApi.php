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
        $volumeResult = Http::get(MangaPassionApi::API_URL . '/editions?order[titleLength]=asc&order[title]=asc&title=' . urlencode($title));
        if (!$volumeResult->successful()) {
            return null;
        }
        $volumeResult = $volumeResult->json();
        if (empty($volumeResult)) {
            return null;
        }
        $result = $volumeResult[0];
        $volume = [];
        $volume['mangapassion_id'] = $result['id'];

        return MangaPassionApi::loadSeriesById($result['id']);
    }

    public static function loadSeriesById(int $mangaPassionId)
    {
        $result = self::seriesRequest($mangaPassionId);
        if (empty($result)) {
            return null;
        }
        $volume = [];
        $volume['mangapassion_id'] = $result['id'];

        $volume['name'] = $result['title'];
        $volume['description'] = $result['description'] ?? '';
        $volume['status'] = $result['status'] ?? SeriesStatus::ANNOUNCED;
        $volume['image_url'] = $result['cover'] ?? '';
        $volume['total'] = $result['numVolumes'] ?? 0;

        if (!empty($result['allInOne'])) {
            $isbn = !empty($result['allInOne']['isbn13']) ? (string) Isbn::of($result['allInOne']['isbn13'])->to13() : null;
            $publish_date = !empty($result['allInOne']['date']) ? new DateTime($result['allInOne']['date']) : null;
            $price = !empty($result['allInOne']['price']) ? floatval($result['allInOne']['price']) / 100.0 : 0.00;
            $pages = !empty($result['allInOne']['pages']) ? $result['allInOne']['pages'] : null;
            $volume['allInOne'] = true;
            $volume['isbn'] = $isbn;
            $volume['publish_date'] = $publish_date?->format('Y-m-d');
            $volume['price'] = $price;
            $volume['pages'] = $pages;
        }

        if (!empty($result['sources'])) {
            $source = $result['sources'][0];
            if (($source['volumes'] ?? 0) > $volume['total']) {
                $volume['total'] = $source['volumes'];
            }
            $volume['source_status'] = $source['status'] ?? null;
            $volume['source_name'] = $source['title'] ?? $source['romaji'] ?? null;
            $volume['source_name_romaji'] = $source['romaji'] ?? null;
            if (!empty($source['tags'])) {
                $tags = collect($source['tags']);
                $volume['demographics'] = $tags->where('type', '=', '0')->pluck('name')->first();
                $volume['genres'] = $tags->where('type', '=', '1')->pluck('name');
            }
            if (!empty($source['tags'])) {
                $magazines = collect($source['magazines']);
                $volume['magazines'] = $magazines->pluck('name');
            }
        }

        $defaultPrice = MangaPassionApi::getDefaultPrice($volume['mangapassion_id']);
        $volume['default_price'] = $defaultPrice;

        if (!empty($result['publishers'])) {
            $volume['publisher'] = $result['publishers'][0]['name'];
        }

        return $volume;
    }

    public static function loadVolumes($mangaPassionId, $total)
    {
        $result = [];
        $pages = intval(ceil($total / 100));
        for ($i = 1; $i <= $pages; $i++) {
            $volumeResult = self::seriesRequest($mangaPassionId, 'volumes?itemsPerPage=100&page=' . $i . '&order[number]=asc');
            if (empty($volumeResult)) {
                return $result;
            }
            foreach ($volumeResult as $volume) {
                if (empty($volume['number']) || empty($volume['id'])) {
                    continue;
                }
                $volume = self::volumeRequest($volume['id']);
                $number = $volume['number'];
                $publish_date = null;
                if ($volume['status'] < 2) {
                    $publish_date = !empty($volume['date']) ? new DateTime($volume['date']) : null;
                }
                $isbn = !empty($volume['isbn13']) ? (string) Isbn::of($volume['isbn13'])->to13() : null;
                $price = !empty($volume['price']) ? floatval($volume['price']) / 100.0 : 0.00;
                $pages = $volume['pages'] ?? null;
                $cover = $volume['cover'] ?? null;
                $result[] = [
                    'number' => $number,
                    'isbn' => $isbn,
                    'publish_date' => $publish_date?->format('Y-m-d'),
                    'price' => $price,
                    'image_url' => $cover,
                    'pages' => $pages,
                ];
            }
        }

        return collect($result);
    }

    public static function getDefaultPrice($mangaPassionId): ?float
    {
        $volumesResult = self::seriesRequest($mangaPassionId, 'volumes?itemsPerPage=1&order[number]=asc');
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

    private static function seriesRequest($mangaPassionId, $additionalParameters = null)
    {
        $url = MangaPassionApi::API_URL . '/editions/' . $mangaPassionId;
        if (!empty($additionalParameters)) {
            $url .= '/' . $additionalParameters;
        }

        $volumeResult = Http::get($url);
        if ($volumeResult->successful()) {
            return $volumeResult->json();
        }

        return null;
    }

    private static function volumeRequest($mangaPassionId, $additionalParameters = null)
    {
        $url = MangaPassionApi::API_URL . '/volumes/' . $mangaPassionId;
        if (!empty($additionalParameters)) {
            $url .= '/' . $additionalParameters;
        }

        $volumeResult = Http::get($url);
        if ($volumeResult->successful()) {
            return $volumeResult->json();
        }

        return null;
    }
}
