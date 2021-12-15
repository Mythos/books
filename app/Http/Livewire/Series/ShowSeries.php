<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Series;
use Livewire\Component;

class ShowSeries extends Component
{
    public Category $category;
    public Series $series;

    public function mount(Category $category, Series $series)
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        return view('livewire.series.show-series')->extends('layouts.app')->section('content');
    }
}
