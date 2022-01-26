<?php

namespace App\Http\Livewire\Series;

use App\Models\Volume;
use Illuminate\Support\Str;
use Livewire\Component;

class UpcomingSeries extends Component
{
    public $upcoming;

    private string $search;

    protected $listeners = ['search' => 'filter'];

    public function render()
    {
        $upcoming = Volume::with('series.publisher')->where('ignore_in_upcoming', 'false')->whereIn('status', [0, 1, 2])->orderBy('publish_date')->get();
        if (!empty($this->search)) {
            $upcoming = $upcoming->filter(function ($volume) {
                return Str::contains(Str::lower($volume->name), Str::lower($this->search))
                || Str::contains(Str::lower($volume->isbn), Str::lower($this->search))
                || Str::contains(Str::lower($volume->series->publisher?->name), Str::lower($this->search));
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
        $this->emitTo('global-statistics', '$refresh');
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }
}
