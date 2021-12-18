<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class CreateCategory extends Component
{
    public string $name = '';

    protected $rules = [
        'name' => 'required'
    ];

    public function render()
    {
        return view('livewire.categories.create-category')->extends('layouts.app')->section('content');
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save() {
        $this->validate();
        $category = new Category(['name' => $this->name]);
        $category->save();
        toastr()->addSuccess(__('Category has been created'));
        return redirect()->route('home');
    }
}
