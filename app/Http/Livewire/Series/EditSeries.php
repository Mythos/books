<?php

namespace App\Http\Livewire\Series;

use App\Models\Series;
use Exception;
use Illuminate\Support\Facades\Log;
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
        'series.language' => 'required',
        'image_url' => 'url'
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
        try {
            $image = $this->getImage();
            $this->series->save();
            $this->storeImage($image);
            toastr()->livewire()->addSuccess(__('Series :name has been updated', ['name' => $this->series->name]));
            $this->reset(['image_url']);
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->livewire()->addError(__('Series :name could not be updated', ['name' => $this->series->name]));
        }
    }

    private function getImage()
    {
        if (empty($this->image_url)) {
            return;
        }
        $image = Image::make($this->image_url)->resize(null, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
        return $image;
    }

    private function storeImage($image)
    {
        if (empty($image)) {
            return;
        }
        Storage::put('public/series/' . $this->series->id . '.jpg', $image);
    }
}
