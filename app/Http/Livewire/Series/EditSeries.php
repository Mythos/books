<?php

namespace App\Http\Livewire\Series;

use App\Models\Series;
use App\Models\Volume;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Image;
use Livewire\Component;
use Storage;

class EditSeries extends Component
{
    public Series $series;

    public string $image_url = '';

    protected $rules = [
        'series.name' => 'required',
        'series.status' => 'required|integer|min:0',
        'series.total' => 'nullable|integer|min:1',
        'series.category_id' => 'required|exists:categories,id',
        'series.is_nsfw' => 'boolean',
        'series.default_price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
        'image_url' => 'url',
    ];

    public function updated($property, $value)
    {
        if ($property == 'series.total' && empty($value)) {
            $this->series->total = null;
        }
        $this->validateOnly($property);
    }

    public function mount(Series $series)
    {
        $this->series = $series;
    }

    public function render()
    {
        return view('livewire.series.edit-series')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        if (!empty($this->series->default_price)) {
            $this->series->default_price = floatval(Str::replace(',', '.', $this->series->default_price));
        }
        try {
            $image = $this->getImage();
            $this->series->save();
            $this->storeImages($image);
            $this->updatePrices();
            toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $this->series->name]));
            $this->reset(['image_url']);
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->livewire()->addError(__(':name could not be updated', ['name' => $this->series->name]));
        }
    }

    public function delete()
    {
        Volume::whereSeriesId($this->series->id)->delete();
        $this->series->delete();
        Storage::deleteDirectory('public/series/' . $this->series->id);
        toastr()->addSuccess(__(':name has been deleted', ['name' => $this->series->name]));
        redirect()->route('categories.show', [$this->series->category]);
    }

    private function getImage()
    {
        if (empty($this->image_url)) {
            return null;
        }
        $image = Image::make($this->image_url)->resize(null, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');

        return $image;
    }

    private function storeImages($image)
    {
        if (empty($image)) {
            return;
        }
        Storage::put('public/series/' . $this->series->id . '/cover.jpg', $image);
        Storage::put('public/series/' . $this->series->id . '/cover_sfw.jpg', $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg'));
    }

    private function updatePrices()
    {
        if (!empty($this->series->default_price) && $this->series->default_price > 0) {
            Volume::whereSeriesId($this->series->id)->whereNull('price')->update(['price' => $this->series->default_price]);
        }
    }
}
