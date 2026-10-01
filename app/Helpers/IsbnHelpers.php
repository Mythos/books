<?php

namespace App\Helpers;

use Exception;
use Http;
use Illuminate\Support\Facades\Log;
use Nicebooks\Isbn\Exception\InvalidIsbnException;
use Nicebooks\Isbn\Isbn;

class IsbnHelpers
{
    public static function convertTo13(?string $isbn): ?string
    {
        if (empty($isbn)) {
            return null;
        }
        try {
            return Isbn::of($isbn)->to13()->toString();
        } catch (InvalidIsbnException) {
            return null;
        }
    }

    public static function format($isbn): ?string
    {
        if (empty($isbn)) {
            return null;
        }
        return Isbn::of($isbn)->toFormattedString();
    }

    public static function getPublishDateByIsbn(?string $isbn): ?string
    {
        $isbn = IsbnHelpers::convertTo13($isbn);
        if (empty($isbn)) {
            return null;
        }
        if (!empty($isbn)) {
            try {
                $response = Http::get('https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn);
                if ($response['totalItems'] > 0) {
                    $date = $response['items'][0]['volumeInfo']['publishedDate'];
                    if (!empty($date)) {
                        return date('Y-m-d', strtotime($date));
                    }
                }
            } catch (Exception $exception) {
                Log::error($exception);
            }
        }

        return null;
    }
}
