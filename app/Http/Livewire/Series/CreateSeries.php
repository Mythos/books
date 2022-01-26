<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Publisher;
use App\Models\Series;
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
        'series.publisher_id' => 'exists:publishers,id',
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
            toastr()->addSuccess(__(':name has been created', ['name' => $this->series->name]));

            return redirect()->route('home');
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->livewire()->addError(__(':name could not be created', ['name' => $this->series->name]));
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
}
