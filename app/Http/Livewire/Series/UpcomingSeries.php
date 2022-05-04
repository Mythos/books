<?php

namespace App\Http\Livewire\Series;

use App\Models\Volume;
use Illuminate\Support\Str;
use Livewire\Component;

class UpcomingSeries extends Component
{
    public $upcoming;

    public string $search;

    protected $listeners = ['search' => 'filter'];

    public function render()
    {
        $upcoming = Volume::with(['series', 'series.publisher', 'series.genres', 'series.category'])
        ->where('ignore_in_upcoming', 'false')
        ->whereRelation('series', 'status', '<>', '3')
        ->whereIn('status', [0, 1, 2])
        ->whereNotNull('publish_date')
        ->get()
        ->sortBy([
            ['publish_date', 'asc'],
            ['series.name', 'asc'],
        ]);
        if (!empty($this->search)) {
            $upcoming = $upcoming->filter(function ($volume) {
                $volumeNameMatch = Str::contains(Str::lower($volume->name), Str::lower($this->search));
                $volumeIsbnMatch = Str::contains(Str::lower($volume->isbn), Str::lower($this->search));
                $seriesPublisherMatch = Str::contains(Str::lower($volume->series->publisher?->name), Str::lower($this->search));
                $seriesGenreMatch = $volume->series->genres->filter(function ($genre) {
                    return Str::contains(Str::lower($genre->name), Str::lower($this->search));
                })->count() > 0;

                return $volumeNameMatch
                || $volumeIsbnMatch
                || $seriesPublisherMatch
                || $seriesGenreMatch;
            });
        }
        $this->upcoming = $upcoming;

        return view('livewire.series.upcoming-series');
    }

    public function ordered(int $id): void
    {
        $this->setStatus($id, 1);
    }

    public function shipped(int $id): void
    {
        $this->setStatus($id, 2);
    }

    public function delivered(int $id): void
    {
        $this->setStatus($id, 3);
    }

    private function setStatus(int $id, int $status): void
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        $this->emitTo('overview', '$refresh');
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }
}
