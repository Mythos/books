<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

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

    public function save() {
        $this->validate();
        $this->category->save();
        toastr()->addSuccess(__('Category has been updated'));
        return redirect()->route('categories.edit', [$this->category]);
    }
}
