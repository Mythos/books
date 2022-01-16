<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowSeries extends Component
{
    public Category $category;

    public Series $series;

    public Collection $volumes;

    public bool $enable_reordering = false;

    public int $new;

    public int $ordered;

    public int $shipped;

    public int $delivered;

    public int $read;

    public function mount(Category $category, Series $series): void
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->volumes = Volume::whereSeriesId($this->series->id)->orderBy('number')->get();
        $this->new = $this->volumes->where('status', '0')->count();
        $this->ordered = $this->volumes->where('status', '1')->count();
        $this->shipped = $this->volumes->where('status', '2')->count();
        $this->delivered = $this->volumes->where('status', '3')->count();
        $this->read = $this->volumes->where('status', '4')->count();

        return view('livewire.series.show-series')->extends('layouts.app')->section('content');
    }

    public function canceled(int $id): void
    {
        $this->setStatus($id, 0);
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

    public function read(int $id): void
    {
        $this->setStatus($id, 4);
    }

    private function setStatus(int $id, int $status): void
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function toggle_reordering(): void
    {
        $this->enable_reordering = !$this->enable_reordering;
    }

    public function move_up(int $id): void
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

    public function move_down(int $id): void
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
