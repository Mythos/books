<?php

namespace App\Http\Livewire\Series;

use App\Models\Volume;
use App\Models\Category;
use App\Models\Series;
use Cache;
use Http;
use Livewire\Component;

class VolumesTable extends Component
{
    public $volumes = [];
    public Series $series;
    public Category $category;

    public function mount(Category $category, Series $series)
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->volumes = Cache::remember('volumes.table.' . $this->series->id, config('cache.duration'), function () {
            return Volume::whereSeriesId($this->series->id)->orderBy('number')->get();
        });
        return view('livewire.series.volumes-table');
    }

    public function ordered(int $id)
    {
        $this->setStatus($id, 1);
    }

    public function delivered(int $id)
    {
        $this->setStatus($id, 2);
    }

    public function canceled(int $id)
    {
        $this->setStatus($id, 0);
    }

    private function setStatus(int $id, int $status)
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
