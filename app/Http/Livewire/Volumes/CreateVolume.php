<?php

namespace App\Http\Livewire\Volumes;

use App\Helpers\IsbnHelpers;
use App\Models\Series;
use App\Models\Volume;
use Illuminate\Support\Str;
use Intervention\Validation\Rules\Isbn;
use Livewire\Component;

class CreateVolume extends Component
{
    public string $publish_date = '';

    public string $isbn = '';

    public int $status = 0;

    public string $price = '';

    public Series $series;

    public function mount(Series $series): void
    {
        $this->series = $series;
    }

    public function render()
    {
        $this->price = $this->series->default_price ?? '';

        return view('livewire.volumes.create-volume')->extends('layouts.app')->section('content');
    }

    protected function rules()
    {
        return [
            'publish_date' => 'date',
            'status' => 'required|integer|min:0',
            'price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
            'isbn' => ['required', 'unique:volumes,isbn,NULL,id,series_id,' . $this->series->id, new Isbn()],
        ];
    }

    public function updated($property, $value): void
    {
        if ($property == 'isbn') {
            $this->validateOnly($property);
            $isbn = IsbnHelpers::convertTo13($value);
            if (!empty($isbn)) {
                $this->publish_date = IsbnHelpers::getPublishDateByIsbn($isbn) ?? '';
            }
        } else {
            $this->validateOnly($property);
        }
    }

    public function save(): void
    {
        $isbn = IsbnHelpers::convertTo13($this->isbn);
        if (!empty($isbn)) {
            $this->isbn = $isbn;
        }
        $this->validate();
        $number = Volume::whereSeriesId($this->series->id)->max('number') ?? 0;
        if (!empty($this->price)) {
            $this->price = floatval(Str::replace(',', '.', $this->price));
        }
        $volume = new Volume([
            'series_id' => $this->series->id,
            'number' => ++$number,
            'publish_date' => $this->publish_date,
            'isbn' => $this->isbn,
            'status' => $this->status,
            'price' => $this->price,
        ]);
        $volume->save();
        toastr()->livewire()->addSuccess(__('Volumme :number has been created', ['number' => $number]));
        $this->resetExcept('series');
    }
}
