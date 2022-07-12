<?php

namespace App\Http\Livewire\Series;

use App\Constants\SeriesStatus;
use App\Helpers\ImageHelpers;
use App\Helpers\MangaPassionApi;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Volume;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateSeries extends Component
{
    public $publishers;

    public Category $category;

    public Series $series;

    public $apiSeries;

    public bool $create_volumes = false;

    protected $rules = [
        'series.name' => 'required',
        'series.description' => 'nullable',
        'series.status' => 'required|integer|min:0',
        'series.total' => 'nullable|integer|min:1',
        'series.category_id' => 'required|exists:categories,id',
        'series.is_nsfw' => 'boolean',
        'series.default_price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
        'series.publisher_id' => 'nullable|exists:publishers,id',
        'series.subscription_active' => 'boolean',
        'series.mangapassion_id' => 'nullable|integer',
        'series.image_url' => 'nullable|url',
        'series.source_status' => 'required|integer|min:0',
        'series.source_name' => 'nullable',
        'series.source_name_romaji' => 'nullable',
        'series.ignore_in_upcoming' => 'boolean',
    ];

    public function updated($property, $value): void
    {
        if ($property == 'series.total' && empty($value)) {
            $this->series->total = null;
        }
        if ($property == 'series.publisher_id' && empty($value)) {
            $this->series->publisher_id = null;
        }
        if ($property == 'series.status' && $value == SeriesStatus::CANCELED && $this->series->subscription_active) {
            $this->series->subscription_active = false;
        }
        $this->validateOnly($property);
    }

    public function mount(Category $category): void
    {
        $this->publishers = Publisher::orderBy('name')->get();
        $this->category = $category;
        $this->series = new Series([
            'status' => SeriesStatus::ANNOUNCED,
            'source_status' => SeriesStatus::ANNOUNCED,
            'category_id' => $category->id,
            'is_nsfw' => false,
            'subscription_active' => false,
            'ignore_in_upcoming' => false,
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
            $this->series->save();
            ImageHelpers::updateSeriesImage($this->series, true);
            $this->createGenres();
            $this->createVolumes();
            toastr()->addSuccess(__(':name has been created', ['name' => $this->series->name]));

            return redirect()->route('home');
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->addError(__(':name could not be created', ['name' => $this->series->name]));
        }
    }

    public function fetchdata(): void
    {
        $this->validateOnly('series.name');
        $this->series->mangapassion_id = null;
        $this->apiSeries = MangaPassionApi::loadSeriesByTitle($this->series->name);

        if (empty($this->apiSeries)) {
            toastr()->addWarning(__('No entry with the title :name has been found', ['name' => $this->series->name]));

            return;
        }
        $this->create_volumes = true;
        $this->series->mangapassion_id = $this->apiSeries['mangapassion_id'];
        $this->series->name = $this->apiSeries['name'];
        $this->series->description = $this->apiSeries['description'];
        $this->series->status = $this->apiSeries['status'];
        $this->series->total = $this->apiSeries['total'];
        $this->series->default_price = $this->apiSeries['default_price'];
        $this->series->image_url = $this->apiSeries['image_url'];
        $this->series->source_status = $this->apiSeries['source_status'];
        $this->series->source_name = $this->apiSeries['source_name'];
        $this->series->source_name_romaji = $this->apiSeries['source_name_romaji'];

        if (empty($this->apiSeries['publisher'])) {
            return;
        }
        $publisher = Publisher::whereName($this->apiSeries['publisher'])->first();
        if (!empty($publisher)) {
            $this->series->publisher_id = $publisher->id;
        } else {
            $publisher = new Publisher(['name' => $this->apiSeries['publisher']]);
            $publisher->save();

            $this->series->publisher_id = $publisher->id;
            $this->publishers = Publisher::orderBy('name')->get();
        }
    }

    private function createVolumes(): void
    {
        if (!$this->create_volumes) {
            return;
        }
        if (empty($this->series->mangapassion_id)) {
            for ($i = 1; $i <= $this->series->total; $i++) {
                $volume = new Volume([
                    'series_id' => $this->series->id,
                    'number' => $i,
                    'price' => $this->series->default_price,
                    'status' => $this->series->subscription_active,
                ]);
                $volume->save();
            }
        }

        $volumesResult = MangaPassionApi::loadVolumes($this->series->mangapassion_id, $this->series->total ?? 500);

        foreach ($volumesResult as $newVolume) {
            $number = $newVolume['number'];
            $isbn = $newVolume['isbn'];
            $publish_date = $newVolume['publish_date'];
            $price = $newVolume['price'];
            $image_url = $newVolume['image_url'];

            $volume = new Volume([
                'series_id' => $this->series->id,
                'isbn' => $isbn,
                'number' => $number,
                'publish_date' => !empty($publish_date) ? $publish_date->format('Y-m-d') : null,
                'price' => $price,
                'status' => $this->series->subscription_active,
                'image_url' => $image_url,
            ]);

            $volume->save();
            ImageHelpers::updateVolumeImage($volume, true);
        }
    }

    private function createGenres(): void
    {
        $genres = [];
        if (!empty($this->apiSeries['demographics'])) {
            $demographics = Genre::whereType(0)->firstOrCreate([
                'name' => $this->apiSeries['demographics'],
                'type' => 0,
            ]);
            $genres[] = $demographics->id;
        }
        if (!empty($this->apiSeries['genres'])) {
            foreach ($this->apiSeries['genres'] as $genreName) {
                $genre = Genre::whereType(1)->firstOrCreate([
                    'name' => $genreName,
                    'type' => 1,
                ]);
                $genres[] = $genre->id;
            }
        }
        $this->series->genres()->sync($genres);
    }
}
