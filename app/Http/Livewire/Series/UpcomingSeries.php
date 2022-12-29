<?php

namespace App\Http\Livewire\Series;

use App\Constants\SeriesStatus;
use App\Constants\VolumeStatus;
use App\Models\Volume;
use Livewire\Component;

class UpcomingSeries extends Component
{
    public $upcoming;

    public string $search;

    public bool $expanded = false;

    protected $listeners = ['search' => 'filter'];

    public function render()
    {
        $upcomingQuery = Volume::with(['series:id,name,slug,category_id,status,subscription_active,source_name,source_name_romaji,ignore_in_upcoming', 'series.publisher:id,name', 'series.genres:id,name', 'series.category:id,name,slug'])
                               ->where('ignore_in_upcoming', 'false')
                               ->whereRelation('series', 'status', '<>', SeriesStatus::CANCELED)
                               ->whereRelation('series', 'ignore_in_upcoming', '=', 'false')
                               ->whereIn('status', [VolumeStatus::NEW, VolumeStatus::ORDERED, VolumeStatus::SHIPPED])
                               ->whereNotNull('publish_date');

        if (!empty($this->search)) {
            $upcomingQuery->where(function ($query): void {
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
                              })
                              ->orWhereHas('magazines', function ($query): void {
                                  $query->where('name', 'like', '%' . $this->search . '%');
                              });
                      });
            });
        }
        $this->upcoming = $upcomingQuery->get()
                                        ->sortBy([
                                            ['publish_date', 'asc'],
                                            ['series.name', 'asc'],
                                        ]);

        return view('livewire.series.upcoming-series');
    }

    public function ordered(int $id): void
    {
        $this->setStatus($id, VolumeStatus::ORDERED);
    }

    public function shipped(int $id): void
    {
        $this->setStatus($id, VolumeStatus::SHIPPED);
    }

    public function delivered(int $id): void
    {
        $this->setStatus($id, VolumeStatus::DELIVERED);
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }

    public function expand(): void
    {
        $this->expanded = !$this->expanded;
    }

    private function setStatus(int $id, int $status): void
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        $this->emitTo('overview', '$refresh');
        $this->emitTo('series.reading-stack-unplanned', '$refresh');
        toastr()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
