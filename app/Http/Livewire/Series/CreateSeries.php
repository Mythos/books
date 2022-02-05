<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Volume;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as FacadesImage;
use Intervention\Image\Image;
use Livewire\Component;
use Nicebooks\Isbn\Isbn;
use Storage;

class CreateSeries extends Component
{
    public $publishers;

    public Category $category;

    public Series $series;

    public string $image_url = '';

    protected $rules = [
        'series.name' => 'required',
        'series.status' => 'required|integer|min:0',
        'series.total' => 'nullable|integer|min:1',
        'series.category_id' => 'required|exists:categories,id',
        'series.is_nsfw' => 'boolean',
        'series.default_price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
        'series.publisher_id' => 'nullable|exists:publishers,id',
        'series.subscription_active' => 'boolean',
        'series.mangapassion_id' => 'nullable|integer',
        'image_url' => 'required|url',
    ];

    public function updated($property, $value): void
    {
        if ($property == 'series.total' && empty($value)) {
            $this->series->total = null;
        }
        if ($property == 'series.publisher_id' && empty($value)) {
            $this->series->publisher_id = null;
        }
        $this->validateOnly($property);
    }

    public function mount(Category $category): void
    {
        $this->publishers = Publisher::orderBy('name')->get();
        $this->category = $category;
        $this->series = new Series([
            'status' => 0,
            'category_id' => $category->id,
            'is_nsfw' => false,
            'subscription_active' => false
        ]);
    }

    public function render()
    {
        return view('livewire.series.create-series')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        if (!empty($this->series->default_price)) {
            $this->series->default_price = floatval(Str::replace(',', '.', $this->series->default_price));
        }
        $this->series->category_id = $this->category->id;
        try {
            $image = $this->getImage();
            $this->series->save();
            $this->storeImages($image);

            $this->createVolumes();

            toastr()->addSuccess(__(':name has been created', ['name' => $this->series->name]));

            return redirect()->route('home');
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->livewire()->addError(__(':name could not be created', ['name' => $this->series->name]));
        }
    }

    public function fetchdata(): void
    {
        $this->validateOnly('series.name');
        $this->series->mangapassion_id = null;
        $response = Http::get('https://api.manga-passion.de/editions?order[titleLength]=asc&order[title]=asc&title=' . urlencode($this->series->name));
        if ($response->successful()) {
            $response = $response->json();
            if (count($response) == 0) {
                return;
            }
            $result = $response[0];
            if (!empty($result['id'])) {
                $this->series->mangapassion_id = $result['id'];
            }
            if (!empty($result['title'])) {
                $this->series->name = $result['title'];
            }
            if (!empty($result['status'])) {
                if ($result['status'] == 1) {
                    $this->series->status = 1;
                } elseif ($result['status'] == 2) {
                    $this->series->status = 2;
                } else {
                    $this->series->status = 0;
                }
            }
            if (!empty($result['cover'])) {
                $this->image_url = $result['cover'];
            }
            if (!empty($result['sources'])) {
                $sourceId = $result['sources'][0]['id'];
                $sourceResponse = Http::get('https://api.manga-passion.de/sources/' . $sourceId);
                if ($sourceResponse->successful()) {
                    $source = $sourceResponse->json();
                    if (!empty($source)) {
                        if (!empty($source['volumes'])) {
                            $this->series->total = $source['volumes'];
                        }
                    }
                }
            }

            $volumesResponse = Http::get('https://api.manga-passion.de/editions/' . $this->series->mangapassion_id . '/volumes?itemsPerPage=1&order[number]=asc');
            if ($volumesResponse->successful()) {
                $volumesResult = $volumesResponse->json();
                if (count($volumesResult) > 0) {
                    foreach ($volumesResult as $volumeResult) {
                        if (empty($volumeResult['price'])) {
                            continue;
                        }
                        $this->series->default_price = !empty($volumeResult['price']) ? floatval($volumeResult['price']) / 100.0 : 0;
                    }
                }
            }

            if (!empty($result['publishers'])) {
                $publisherName = $result['publishers'][0]['name'];
                $publisher = Publisher::whereName($publisherName)->firstOrCreate([
                    'name' => $publisherName,
                ]);
                $this->publishers = Publisher::orderBy('name')->get();
                $this->series->publisher_id = $publisher->id;
            }
        }
    }

    private function getImage(): ?Image
    {
        if (empty($this->image_url)) {
            return null;
        }
        $image = FacadesImage::make($this->image_url)->resize(null, 400, function ($constraint): void {
            $constraint->aspectRatio();
        })->encode('jpg');

        return $image;
    }

    private function storeImages($image): void
    {
        if (empty($image)) {
            return;
        }
        Storage::put('public/series/' . $this->series->id . '/cover.jpg', $image);
        Storage::put('public/series/' . $this->series->id . '/cover_sfw.jpg', $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg'));
    }

    private function createVolumes(): void
    {
        if (empty($this->series->mangapassion_id)) {
            return;
        }
        $seriesId = $this->series->id;
        $volumesResponse = Http::get('https://api.manga-passion.de/editions/' . $this->series->mangapassion_id . '/volumes?itemsPerPage=500&order[number]=asc');
        if ($volumesResponse->successful()) {
            $volumesResult = $volumesResponse->json();
            if (count($volumesResult) > 0) {
                foreach ($volumesResult as $volumeResult) {
                    if (empty($volumeResult['isbn13'])) {
                        continue;
                    }
                    $publish_date = !empty($volumeResult['date']) ? new DateTime($volumeResult['date']) : null;
                    $volume = new Volume([
                        'series_id' => $seriesId,
                        'isbn' => Isbn::of($volumeResult['isbn13'])->to13(),
                        'number' => $volumeResult['number'],
                        'publish_date' => !empty($publish_date) ? $publish_date->format('Y-m-d') : null,
                        'price' => !empty($volumeResult['price']) ? floatval($volumeResult['price']) / 100.0 : 0,
                        'status' => $this->series->subscription_active,
                    ]);
                    $volume->save();
                }
            }
        }
    }
}
