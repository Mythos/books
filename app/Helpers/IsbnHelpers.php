<?php

namespace App\Helpers;

use Http;
use Nicebooks\Isbn\Isbn;
use Nicebooks\Isbn\IsbnTools;

class IsbnHelpers
{
    public static function convertTo13(?string $isbn): ?string
    {
        if (empty($isbn)) {
            return null;
        }
        $tools = new IsbnTools();
        if ($tools->isValidIsbn($isbn)) {
            return Isbn::of($isbn)->to13();
        }
        return null;
    }

    public static function format($isbn): ?string
    {
        if (empty($isbn)) {
            return null;
        }
        $tools = new IsbnTools();
        return $tools->format($isbn);
    }

    public static function getPublishDateByIsbn(?string $isbn): ?string
    {
        $isbn = IsbnHelpers::convertTo13($isbn);
        if (empty($isbn)) {
            return null;
        }
        if (!empty($isbn)) {
            $response = Http::get('https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn);
            if ($response['totalItems'] > 0) {
                $date = $response["items"][0]["volumeInfo"]["publishedDate"];
                if (!empty($date)) {
                    return date('Y-m-d', strtotime($date));
                }
            }
        }
        return null;
    }
}
