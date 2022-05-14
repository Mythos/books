<?php

namespace App\Http\Livewire\Series;

use App\Constants\SeriesStatus;
use App\Models\Category;
use App\Models\Series;
use Livewire\Component;

class Gallery extends Component
{
    public $series = [];

    public string $search = '';

    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    protected $listeners = ['search' => 'filter'];

    public function render()
    {
        $this->series = Series::whereCategoryId($this->category->id)->with(['volumes', 'publisher']);
        $show_canceled_series = session('show_canceled_series') ?? false;
        if (!$show_canceled_series) {
            $this->series->where('status', '<>', SeriesStatus::Canceled);
        }
        if (!empty($this->search)) {
            $this->series->where('name', 'like', '%' . $this->search . '%')
                   ->orWhereHas('volumes', function ($query): void {
                       $query->where('isbn', 'like', '%' . $this->search . '%');
                   })
                   ->orWhereHas('publisher', function ($query): void {
                       $query->where('name', 'like', '%' . $this->search . '%');
                   })
                   ->orWhereHas('genres', function ($query): void {
                       $query->where('name', 'like', '%' . $this->search . '%');
                   });
        }
        $this->series = $this->series->orderBy('status')->orderBy('name')->get();

        return view('livewire.series.gallery');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }
}
