<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Series;
use Exception;
use Illuminate\Support\Facades\Log;
use Image;
use Livewire\Component;
use Storage;

class CreateSeries extends Component
{
    public Category $category;
    public Series $series;

    public string $image_url = '';

    protected $rules = [
        'series.name' => 'required',
        'series.status' => 'required|integer|min:0',
        'series.total' => 'nullable|integer|min:1',
        'series.category_id' => 'required|exists:categories,id',
        'series.is_nsfw' => 'boolean',
        'image_url' => 'required|url'
    ];

    public function updated($property, $value)
    {
        if ($property == 'series.total' && empty($value)) {
            $this->series->total = null;
        }
        $this->validateOnly($property);
    }

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->series = new Series([
            'status' => 0,
            'category_id' => $category->id,
            'is_nsfw' => false
        ]);
    }

    public function render()
    {
        return view('livewire.series.create-series')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        $this->series->category_id = $this->category->id;
        try {
            $image = $this->getImage();
            $this->series->save();
            $this->storeImages($image);
            toastr()->addSuccess(__('Series :name has been created', ['name' => $this->series->name]));
            return redirect()->route('home');
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->livewire()->addError(__('Series :name could not be created', ['name' => $this->series->name]));
        }
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
}
