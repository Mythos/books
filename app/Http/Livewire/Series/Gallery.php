<?php

namespace App\Http\Livewire\Series;

use App\Constants\SeriesStatus;
use App\Models\Category;
use App\Models\Series;
use Livewire\Component;
use Livewire\WithPagination;

class Gallery extends Component
{
    use WithPagination;

    public string $search = '';

    public Category $category;

    public int $total = 0;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['search' => 'filter', 'show_nsfw' => '$refresh', 'show_canceled_series' => '$refresh'];

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function render()
    {
        $series = Series::whereCategoryId($this->category->id)->with(['volumes:id,status,publish_date,series_id', 'publisher:id,name']);
        $show_canceled_series = session('show_canceled_series') ?? false;
        if (!$show_canceled_series) {
            $series->where('status', '<>', SeriesStatus::CANCELED);
        }
        if (!empty($this->search)) {
            $series->where(function ($query): void {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('source_name', 'like', '%' . $this->search . '%')
                      ->orWhere('source_name_romaji', 'like', '%' . $this->search . '%')
                      ->orWhereHas('volumes', function ($query): void {
                          $query->where('isbn', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('publisher', function ($query): void {
                          $query->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('genres', function ($query): void {
                          $query->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('magazines', function ($query): void {
                          $query->where('name', 'like', '%' . $this->search . '%');
                      });
            });
        }
        $series = $series->orderBy('status')->orderBy('name');
        $this->total = $series->count();
        if (!empty($this->category->page_size)) {
            $series = $series->paginate($this->category->page_size, ['*'], $this->category->slug);
        } else {
            $series = $series->get();
        }

        return view('livewire.series.gallery', [
            'series' => $series,
        ]);
    }

    public function filter($filter): void
    {
        $this->search = $filter;
        $this->resetPage($this->category->slug);
    }
}
