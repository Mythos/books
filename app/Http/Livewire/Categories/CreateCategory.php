<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class CreateCategory extends Component
{
    public string $name = '';
    public int $sort_index = 0;

    protected $rules = [
        'name' => 'required',
        'sort_index' => 'required|integer|min:0',
    ];

    public function mount()
    {
        $this->sort_index = (Category::all()->max('sort_index') ?? 0) + 1;
    }

    public function render()
    {
        return view('livewire.categories.create-category')->extends('layouts.app')->section('content');
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();
        $category = new Category(['name' => $this->name, 'sort_index' => $this->sort_index]);
        $category->save();
        toastr()->addSuccess(__(':name has been created', ['name' => $this->name]));
        return redirect()->route('home');
    }
}
