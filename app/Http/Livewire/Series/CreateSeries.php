<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Series;
use Exception;
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
        'series.language' => 'required',
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
            'category_id' => $category->id
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
            $this->storeImage($image);
            toastr()->addSuccess(__('Series :name has been created', ['name' => $this->series->name]));
            redirect()->route('home');
        } catch (Exception $exception) {
            toastr()->livewire()->addError(__('Series :name could not be created', ['name' => $this->series->name]));
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
        if(empty($image)) {
            return;
        }
        Storage::put('public/series/' . $this->series->id . '.jpg', $image);
    }
}
