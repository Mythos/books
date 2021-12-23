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
        'category.name' => 'required'
    ];

    public function render()
    {
        return view('livewire.categories.edit-category')->extends('layouts.app')->section('content');
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();
        $this->category->save();
        toastr()->livewire()->addSuccess(__('Category has been updated'));
    }

    public function delete()
    {
        $series = Series::whereCategoryId($this->category->id)->get();
        foreach ($series as $s) {
            Volume::whereSeriesId($s->id)->delete();
            $s->delete();
            Storage::delete('public/series/' . $s->id . '.jpg');
        }
        $this->category->delete();
        toastr()->addSuccess(__('Category :name has been deleted', ['name' => $this->category->name]));
        return redirect()->route('home');
    }
}
