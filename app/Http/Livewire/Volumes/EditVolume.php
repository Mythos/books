<?php

namespace App\Http\Livewire\Volumes;

use App\Helpers\ImageHelpers;
use App\Helpers\IsbnHelpers;
use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use App\Rules\Isbn;
use App\Services\SeriesService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditVolume extends Component
{
    use LivewireAlert;

    public Category $category;

    public Series $series;

    public Volume $volume;

    public $seriesService;

    protected $listeners = [
        'confirmedDelete',
    ];

    public function mount(Category $category, Series $series, int $number): void
    {
        $this->category = $category;
        $this->series = $series;
        $this->volume = Volume::whereSeriesId($series->id)->whereNumber($number)->first();
    }

    public function updated($property, $value): void
    {
        if ($property == 'volume.isbn') {
            $this->validateOnly($property);
            $isbn = IsbnHelpers::convertTo13($value);
            if (!empty($isbn)) {
                $this->volume->publish_date = IsbnHelpers::getPublishDateByIsbn($isbn) ?? null;
            }
        } else {
            $this->validateOnly($property);
        }
    }

    public function render()
    {
        return view('livewire.volumes.edit-volume')->extends('layouts.app')->section('content');
    }

    public function save(SeriesService $seriesService)
    {
        $isbn = IsbnHelpers::convertTo13($this->volume->isbn);
        if (!empty($isbn)) {
            $this->volume->isbn = $isbn;
        }
        if (!empty($this->volume->price)) {
            $this->volume->price = floatval(Str::replace(',', '.', $this->volume->price));
        } else {
            $this->volume->price = 0;
        }
        if (empty($this->volume->publish_date)) {
            $this->volume->publish_date = null;
        }
        $this->validate();
        $this->volume->save();
        ImageHelpers::updateVolumeImage($this->volume);

        $seriesService->resetNumbers($this->volume->series_id);
        toastr()->addSuccess(__('Volumme :number has been updated', ['number' => $this->volume->number]));

        return redirect()->route('series.show', [$this->category, $this->series]);
    }

    public function delete(): void
    {
        $this->confirm(__('Are you sure you want to delete :name?', ['name' => __('Volume :number', ['number' => $this->volume->number])]), [
            'confirmButtonText' => __('Delete'),
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'confirmedDelete',
        ]);
    }

    public function confirmedDelete()
    {
        $this->volume->delete();
        Storage::disk('public')->deleteDirectory($this->volume->image_path);
        Storage::disk('public')->deleteDirectory('thumbnails/' . $this->volume->image_path);
        toastr()->addSuccess(__('Volumme :number has been deleted', ['number' => $this->volume->number]));

        return redirect()->route('series.show', [$this->category, $this->series]);
    }

    protected function rules()
    {
        return [
            'volume.number' => 'required|integer|min:1',
            'volume.publish_date' => 'nullable|date',
            'volume.status' => 'required|integer|min:0',
            'volume.price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
            'volume.isbn' => ['nullable', 'unique:volumes,isbn,' . $this->volume->id . ',id,series_id,' . $this->series->id, new Isbn()],
            'volume.ignore_in_upcoming' => 'boolean',
            'volume.series_id' => 'required|exists:series,id',
            'volume.image_url' => 'nullable|url',
            'volume.pages' => 'nullable|integer|min:0',
            'volume.chapters' => 'nullable|integer|min:0',
        ];
    }
}
