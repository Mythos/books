<?php

namespace App\Http\Livewire\Series;

use App\Models\Volume;
use Cache;
use Livewire\Component;

class UpcomingSeries extends Component
{
    public $upcoming;

    public function render()
    {
        $this->upcoming = Cache::remember('upcoming', 60 * 10, function () {
            return Volume::with('series')->where('status', '!=', '2')->orderBy('publish_date')->get();
        });
        return view('livewire.series.upcoming-series');
    }

    public function ordered(int $id)
    {
        $this->setStatus($id, 1);
    }

    public function delivered(int $id)
    {
        $this->setStatus($id, 2);
    }

    private function setStatus(int $id, int $status)
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
