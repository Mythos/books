<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Series;
use Livewire\Component;

class Gallery extends Component
{
    public $series = [];

    private string $search = '';

    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    protected $listeners = ['search' => 'filter'];

    public function render()
    {
        $series = Series::whereCategoryId($this->category->id)->with('volumes');
        if (!empty($this->search)) {
            $series->where('name', 'like', '%' . $this->search . '%')
                   ->orWhereHas('volumes', function ($query): void {
                       $query->where('isbn', 'like', '%' . $this->search . '%');
                   });
        }
        $this->series = $series->orderBy('status')->orderBy('name')->get();

        return view('livewire.series.gallery');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }
}
