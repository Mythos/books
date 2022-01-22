<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use Livewire\Component;
use Storage;

class EditCategory extends Component
{
    public Category $category;

    protected $rules = [
        'category.name' => 'required',
        'category.sort_index' => 'required|integer|min:0',
        'category.type' => 'required|integer|min:0',
    ];

    public function render()
    {
        return view('livewire.categories.edit-category')->extends('layouts.app')->section('content');
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function save(): void
    {
        $this->validate();
        $this->category->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $this->category->name]));
    }

    public function delete()
    {
        $series = Series::whereCategoryId($this->category->id)->get();
        foreach ($series as $s) {
            Volume::whereSeriesId($s->id)->delete();
            $s->delete();
            Storage::deleteDirectory('public/series/' . $s->id);
        }
        $this->category->delete();
        toastr()->addSuccess(__(':name has been deleted', ['name' => $this->category->name]));

        return redirect()->route('home');
    }
}
