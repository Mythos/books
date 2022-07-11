<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class CreateCategory extends Component
{
    public Category $category;

    protected $rules = [
        'category.name' => 'required',
        'category.sort_index' => 'required|integer|min:0',
        'category.type' => 'required|integer|min:0',
        'category.page_size' => 'nullable|integer|min:0',
    ];

    public function mount(): void
    {
        $this->category = new Category([
            'sort_index' => 0,
            'type' => 0,
            'page_size' => 40,
            'sort_index' => (Category::all()->max('sort_index') ?? 0) + 1,
        ]);
    }

    public function render()
    {
        return view('livewire.categories.create-category')->extends('layouts.app')->section('content');
    }

    public function updated($property, $value): void
    {
        if ($property == 'category.page_size') {
            $this->category->page_size = !empty($value) ? $value : null;
        }
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();
        $this->category->save();
        toastr()->addSuccess(__(':name has been created', ['name' => $this->category->name]));

        return redirect()->route('home');
    }
}
