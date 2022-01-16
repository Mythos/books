<?php

namespace App\Http\Livewire\Series;

use App\Models\Volume;
use Livewire\Component;

class UpcomingSeries extends Component
{
    public $upcoming;

    public function render()
    {
        $this->upcoming = Volume::with('series')->whereIn('status', [0, 1, 2])->orderBy('publish_date')->get();

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
}
