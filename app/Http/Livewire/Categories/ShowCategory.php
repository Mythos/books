<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class ShowCategory extends Component
{
    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.categories.show-category')->extends('layouts.app')->section('content');
    }
}
