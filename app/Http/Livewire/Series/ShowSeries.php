<?php

namespace App\Http\Livewire\Series;

use App\Constants\VolumeStatus;
use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use App\Services\SeriesService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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

    protected $listeners = ['show_nsfw' => '$refresh'];

    public function mount(Category $category, Series $series): void
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->series = Series::with('genres')->find($this->series->id);
        $this->volumes = Volume::whereSeriesId($this->series->id)->orderBy('number')->get();
        $this->new = $this->volumes->where('status', VolumeStatus::NEW)->count();
        $this->ordered = $this->volumes->where('status', VolumeStatus::ORDERED)->count();
        $this->shipped = $this->volumes->where('status', VolumeStatus::SHIPPED)->count();
        $this->delivered = $this->volumes->where('status', VolumeStatus::DELIVERED)->count();
        $this->read = $this->volumes->where('status', VolumeStatus::READ)->count();

        return view('livewire.series.show-series')->extends('layouts.app')->section('content');
    }

    public function canceled(int $id): void
    {
        $this->setStatus($id, VolumeStatus::NEW);
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

    public function read(int $id): void
    {
        $this->setStatus($id, VolumeStatus::READ);
    }

    public function toggle_reordering(): void
    {
        $this->enable_reordering = !$this->enable_reordering;
    }

    public function move_up(int $id, SeriesService $seriesService): void
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

        $seriesService->resetNumbers($this->series->id);

        toastr()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function move_down(int $id, SeriesService $seriesService): void
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

        $seriesService->resetNumbers($this->series->id);

        toastr()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function update(SeriesService $seriesService)
    {
        try {
            $seriesService->refreshMetadata($this->series);
            $seriesService->updateVolumes($this->series);

            toastr()->addSuccess(__(':name has been updated', ['name' => $this->series->name]));

            return redirect(request()->header('Referer'));
        } catch (Exception $exception) {
            Log::error('Error while updating series via API', ['exception' => $exception]);
            toastr()->addError(__(':name could not be updated', ['name' => $this->series->name]));
        }
    }

    private function setStatus(int $id, int $status): void
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        toastr()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
