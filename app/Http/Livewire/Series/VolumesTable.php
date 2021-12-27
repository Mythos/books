<?php

namespace App\Http\Livewire\Series;

use App\Models\Volume;
use App\Models\Category;
use App\Models\Series;
use Cache;
use Http;
use Illuminate\Support\Collection;
use Livewire\Component;

class VolumesTable extends Component
{
    public Collection $volumes;
    public Series $series;
    public Category $category;
    public bool $enable_reordering = false;

    public function mount(Category $category, Series $series)
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->volumes = Volume::whereSeriesId($this->series->id)->orderBy('number')->get();
        return view('livewire.series.volumes-table');
    }

    public function canceled(int $id)
    {
        $this->setStatus($id, 0);
    }

    public function ordered(int $id)
    {
        $this->setStatus($id, 1);
    }

    public function shipped(int $id)
    {
        $this->setStatus($id, 2);
    }

    public function delivered(int $id)
    {
        $this->setStatus($id, 3);
    }

    private function setStatus(int $id, int $status)
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function toggle_reordering()
    {
        $this->enable_reordering = !$this->enable_reordering;
    }

    public function move_up(int $id)
    {
        $volume = Volume::find($id);
        if ($volume->number <= 1) {
            return;
        }
        $predecessor = Volume::whereSeriesId($volume->series_id)->orderByDesc('number')->where('number', '<', $volume->number)->first();
        $volume->number = --$volume->number;
        $predecessor->number = ++$predecessor->number;

        $volume->save();
        $predecessor->save();

        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function move_down(int $id)
    {
        $volume = Volume::find($id);
        if ($volume->number >= $this->volumes->max('number')) {
            return;
        }
        $successor = Volume::whereSeriesId($volume->series_id)->orderBy('number')->where('number', '>', $volume->number)->first();
        $volume->number = ++$volume->number;
        $successor->number = --$successor->number;

        $volume->save();
        $successor->save();

        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
