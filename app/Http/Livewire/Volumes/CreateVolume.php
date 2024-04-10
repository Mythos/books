<?php

namespace App\Http\Livewire\Volumes;

use App\Helpers\ImageHelpers;
use App\Helpers\IsbnHelpers;
use App\Models\Series;
use App\Models\Volume;
use App\Rules\Isbn;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateVolume extends Component
{
    public Volume $volume;

    public Series $series;

    public ?string $image_preview = null;

    public function mount(Series $series): void
    {
        $this->series = $series;
        $this->volume = $this->getModelInstance();
    }

    public function render()
    {
        return view('livewire.volumes.create-volume')->extends('layouts.app')->section('content');
    }

    public function updated($property, $value): void
    {
        if ($property == 'volume.isbn') {
            $this->validateOnly($property);
            $isbn = IsbnHelpers::convertTo13($value);
            if (!empty($isbn)) {
                $this->volume->publish_date = IsbnHelpers::getPublishDateByIsbn($isbn) ?? '';
            }
        } elseif ($property == 'volume.image_url') {
            $this->image_preview = ImageHelpers::getImage($this->volume->image_url)?->toDataUri();
        } else {
            $this->validateOnly($property);
        }
    }

    public function save(): void
    {
        $isbn = IsbnHelpers::convertTo13($this->volume->isbn);
        if (!empty($isbn)) {
            $this->volume->isbn = $isbn;
        }
        $this->validate();
        $number = Volume::whereSeriesId($this->series->id)->max('number') ?? 0;
        $this->volume->number = $number + 1;
        if (!empty($this->volume->price)) {
            $this->volume->price = floatval(Str::replace(',', '.', $this->volume->price));
        } else {
            $this->volume->price = 0;
        }
        if (empty($this->volume->publish_date)) {
            $this->volume->publish_date = null;
        }
        if (empty($this->volume->pages)) {
            $this->volume->pages = null;
        }
        $this->volume->plan_to_read = false;
        $this->volume->save();
        ImageHelpers::updateVolumeImage($this->volume, true);

        toastr()->addSuccess(__('Volumme :number has been created', ['number' => $this->volume->number]));
        $this->volume = $this->getModelInstance();
    }

    protected function rules()
    {
        return [
            'volume.publish_date' => 'nullable|date',
            'volume.status' => 'required|integer|min:0',
            'volume.price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
            'volume.isbn' => ['nullable', 'unique:volumes,isbn,NULL,id,series_id,' . $this->series->id, new Isbn()],
            'volume.ignore_in_upcoming' => 'boolean',
            'volume.series_id' => 'required|exists:series,id',
            'volume.image_url' => 'nullable|url',
            'volume.pages' => 'nullable|integer|min:0',
        ];
    }

    private function getModelInstance(): Volume
    {
        return new Volume([
            'series_id' => $this->series->id,
            'price' => $this->series->default_price ?? '',
            'status' => $this->series->subscription_active,
            'ignore_in_upcoming' => false,
            'plan_to_read' => false,
        ]);
    }
}
