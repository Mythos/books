<?php

namespace App\Http\Livewire\Series;

use App\Helpers\MangaPassionApi;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Volume;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as FacadesImage;
use Intervention\Image\Image;
use Livewire\Component;
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
            'subscription_active' => false,
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
        $series = MangaPassionApi::loadSeries($this->series->name);

        if (empty($series)) {
            toastr()->livewire()->addWarning(__('No entry with the title :name has been found', ['name' => $this->series->name]));

            return;
        }

        $this->series->mangapassion_id = $series['mangapassion_id'];
        $this->series->name = $series['name'];
        $this->series->status = $series['status'];
        $this->series->total = $series['total'];
        $this->series->default_price = $series['default_price'];
        $this->image_url = $series['image_url'];

        $publisher = Publisher::whereName($series['publisher'])->first();
        if (!empty($publisher)) {
            $this->series->publisher_id = $publisher->id;
        } else {
            $publisher = new Publisher(['name' => $series['publisher']]);
            $publisher->save();

            $this->series->publisher_id = $publisher->id;
            $this->publishers = Publisher::orderBy('name')->get();
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
        Storage::disk('public')->put($this->series->image_path . '/cover.jpg', $image);
        Storage::disk('public')->put($this->series->image_path . '/cover_sfw.jpg', $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg'));
    }

    private function createVolumes(): void
    {
        if (empty($this->series->mangapassion_id)) {
            return;
        }

        $volumesResult = MangaPassionApi::loadVolumes($this->series->mangapassion_id);

        foreach ($volumesResult as $newVolume) {
            $number = $newVolume['number'];
            $isbn = $newVolume['isbn'];
            $publish_date = $newVolume['publish_date'];
            $price = $newVolume['price'];

            $volume = new Volume([
                'series_id' => $this->series->id,
                'isbn' => $isbn,
                'number' => $number,
                'publish_date' => !empty($publish_date) ? $publish_date->format('Y-m-d') : null,
                'price' => $price,
                'status' => $this->series->subscription_active,
            ]);
            $volume->save();
        }
    }
}
