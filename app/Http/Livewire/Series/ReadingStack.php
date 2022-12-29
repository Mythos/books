<?php

namespace App\Http\Livewire\Series;

use App\Constants\VolumeStatus;
use App\Models\Volume;
use Livewire\Component;

class ReadingStack extends Component
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
                               ->where('plan_to_read', '1');

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
                              })
                              ->orWhereHas('magazines', function ($query): void {
                                  $query->where('name', 'like', '%' . $this->search . '%');
                              });
                      });
            });
        }
        $this->volumes = $readingStackQuery->get()
                                        ->sortBy([
                                            ['series.name', 'asc'],
                                            ['number', 'asc'],
                                        ]);

        return view('livewire.series.reading-stack');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }

    public function read(int $id): void
    {
        $volume = Volume::find($id);
        $volume->status = VolumeStatus::READ;
        $volume->plan_to_read = false;
        $volume->save();
        $this->emitTo('overview', '$refresh');
        toastr()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function expand(): void
    {
        $this->expanded = !$this->expanded;
    }

    public function unplan(int $id): void
    {
        $volume = Volume::find($id);
        $volume->plan_to_read = false;
        $volume->save();
        $this->emitTo('series.reading-stack-unplanned', '$refresh');
        toastr()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
