<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Series;
use Cache;
use Livewire\Component;

class Gallery extends Component
{
    public $series = [];
    public Category $category;

    public function mount(Category $category)
    {
        $this->category = $category;
    }

    public function render()
    {
        $this->series = Series::whereCategoryId($this->category->id)->with('volumes')->orderBy('status')->orderBy('name')->get();
        return view('livewire.series.gallery');
    }
}
