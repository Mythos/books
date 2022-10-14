<?php

namespace App\Http\Livewire\Series;

use App\Constants\SeriesStatus;
use App\Constants\VolumeStatus;
use App\Models\Volume;
use Livewire\Component;

class ReadingStackUnplanned extends Component
{
    public $volumes;

    public bool $expanded = false;

    public string $search;

    protected $listeners = [
        '$refresh',
        'search' => 'filter',
    ];

    public function render()
    {
        $readingStackQuery = Volume::with(['series:id,name,slug,category_id,status,subscription_active,source_name,source_name_romaji,ignore_in_upcoming', 'series.publisher:id,name', 'series.genres:id,name', 'series.category:id,name,slug'])
                                ->whereRelation('series', 'status', '<>', SeriesStatus::CANCELED)
                                ->where('status', VolumeStatus::DELIVERED)
                                ->where('plan_to_read', '0');

        if (!empty($this->search)) {
            $readingStackQuery->where(function ($query): void {
                $query->where('isbn', 'like', '%' . $this->search . '%')
                      ->orWhereHas('series', function ($query): void {
                          $query->where('name', 'like', '%' . $this->search . '%')
                              ->orwhere('source_name', 'like', '%' . $this->search . '%')
                              ->orWhere('source_name_romaji', 'like', '%' . $this->search . '%')
                              ->orWhereHas('publisher', function ($query): void {
                                  $query->where('name', 'like', '%' . $this->search . '%');
                              })
                              ->orWhereHas('genres', function ($query): void {
                                  $query->where('name', 'like', '%' . $this->search . '%');
                              });
                      });
            });
        }
        $groups = $readingStackQuery->get()
                                        ->sortBy([
                                            ['series.name', 'asc'],
                                            ['number', 'asc'],
                                        ])->groupBy('series_id');

        $results = [];
        foreach ($groups as $group) {
            $results[] = $group->first();
        }
        $this->volumes = $results;

        return view('livewire.series.reading-stack-unplanned');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }

    public function expand(): void
    {
        $this->expanded = !$this->expanded;
    }

    public function plan(int $id): void
    {
        $volume = Volume::find($id);
        $volume->plan_to_read = true;
        $volume->save();
        $this->emitTo('series.reading-stack', '$refresh');
        toastr()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
