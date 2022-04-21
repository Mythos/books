<?php

namespace App\Http\Livewire\Series;

use App\Helpers\ImageHelpers;
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

    public function mount(Category $category, Series $series): void
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->series = Series::with('genres')->find($this->series->id);
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

        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
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

        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function update(SeriesService $seriesService): void
    {
        try {
            $this->series = $seriesService->refreshMetadata($this->series);
            $this->series->save();

            $image = ImageHelpers::getImage($this->series->image_url);
            if (!empty($image)) {
                ImageHelpers::storePublicImage($image, $this->series->image_path . '/cover.jpg');
                $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
                ImageHelpers::storePublicImage($nsfwImage, $this->series->image_path . '/cover_sfw.jpg');
            }

            $seriesService->updateVolumes($this->series);

            toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $this->series->name]));
        } catch (Exception $exception) {
            Log::error('Error while updating series via API', ['exception' => $exception]);
            toastr()->livewire()->addError(__(':name could not be updated', ['name' => $this->series->name]));
        }
    }
}
