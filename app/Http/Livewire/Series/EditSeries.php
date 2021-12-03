<?php

namespace App\Http\Livewire\Series;

use App\Models\Series;
use Exception;
use Image;
use Livewire\Component;
use Storage;

class EditSeries extends Component
{
    public Series $series;

    public string $image_url = '';

    protected $rules = [
        'series.name' => 'required',
        'series.status' => 'required',
        'series.total' => 'nullable|integer|min:1',
        'series.category_id' => 'required|exists:categories,id',
        'image_url' => 'url'
    ];

    public function updated($property, $value)
    {
        if ($property == 'series.total' && empty($value)) {
            $this->series->total = null;
        }
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
        try {
            $this->validate();
            $this->series->save();
            $this->storeImage();
            toastr()->livewire()->addSuccess(__('Series :series has been updated', ['series' => $this->series->name]));
        } catch (Exception $exception) {
            toastr()->livewire()->addError(__('Series :series could not be updated', ['series' => $this->series->name]));
        }
    }

    private function storeImage()
    {
        if (empty($this->image_url)) {
            return;
        }
        $image = Image::make($this->image_url)->resize(null, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
        Storage::put('public/series/' . $this->series->id . '.jpg', $image);
    }
}
