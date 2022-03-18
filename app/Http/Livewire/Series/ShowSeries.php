<?php

namespace App\Http\Livewire\Series;

use App\Helpers\ImageHelpers;
use App\Helpers\MangaPassionApi;
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

        $this->resetNumbers();

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

        $this->resetNumbers();

        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function update(): void
    {
        try {
            $service = new SeriesService();
            $this->series = $service->refreshMetadata($this->series);
            $this->series->save();

            $image = ImageHelpers::getImage($this->series->image_url);
            if (!empty($image)) {
                ImageHelpers::storePublicImage($image, $this->series->image_path . '/cover.jpg');
                $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
                ImageHelpers::storePublicImage($nsfwImage, $this->series->image_path . '/cover_sfw.jpg');
            }

            $this->updateVolumes();

            toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $this->series->name]));
        } catch (Exception $exception) {
            Log::error('Error while updating series via API', ['exception' => $exception]);
            toastr()->livewire()->addError(__(':name could not be updated', ['name' => $this->series->name]));
        }
    }

    private function updateVolumes(): void
    {
        if (empty($this->series->mangapassion_id)) {
            return;
        }
        $volumes = Volume::whereSeriesId($this->series->id)->get();

        $volumesResult = MangaPassionApi::loadVolumes($this->series->mangapassion_id);
        $newVolumes = [];

        foreach ($volumesResult as $volumeResult) {
            $number = $volumeResult['number'];
            $isbn = $volumeResult['isbn'];
            $publish_date = $volumeResult['publish_date'];
            $price = $volumeResult['price'];

            $volume = null;
            if (!empty($isbn)) {
                $volume = $volumes->firstWhere('isbn', $isbn);
            }
            if (!empty($number)) {
                $volume = $volumes->firstWhere('number', $number);
            }
            if (empty($volume)) {
                $newVolumes[] = $volumeResult;
                continue;
            }

            if ($volume->status == 0) {
                $volume->price = $price;
            }

            $volume->number = $number;
            $volume->publish_date = !empty($publish_date) ? $publish_date->format('Y-m-d') : null;
            if (!empty($isbn)) {
                $volume->isbn = $isbn;
            }
            $volume->save();
        }

        foreach ($newVolumes as $newVolume) {
            $number = $newVolume['number'];
            $isbn = $newVolume['isbn'];
            $publish_date = $newVolume['publish_date'];
            $price = $newVolume['price'];

            $volume = new Volume([
                'series_id' => $this->series->id,
                'isbn' => $isbn,
                'number' => $number,
                'publish_date' => !empty($publish_date) ? $publish_date->format('Y-m-d') : null,
                'price' => $price,
                'status' => $this->series->subscription_active,
            ]);
            $volume->save();
        }

        $this->resetNumbers();
    }

    private function resetNumbers(): void
    {
        $volumes = Volume::whereSeriesId($this->series->id)->orderBy('number')->get();
        $number = 1;
        foreach ($volumes as $volume) {
            $volume->number = $number;
            $volume->save();
            $number++;
        }
    }
}
