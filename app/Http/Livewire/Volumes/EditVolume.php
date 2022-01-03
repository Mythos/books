<?php

namespace App\Http\Livewire\Volumes;

use App\Helpers\IsbnHelpers;
use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use Illuminate\Support\Str;
use Intervention\Validation\Rules\Isbn;
use Livewire\Component;

class EditVolume extends Component
{
    public Category $category;
    public Series $series;
    public Volume $volume;

    public function mount(Category $category, Series $series, int $number)
    {
        $this->category = $category;
        $this->series = $series;
        $this->volume = Volume::whereSeriesId($series->id)->whereNumber($number)->first();
    }

    protected function rules()
    {
        return [
            'volume.publish_date' => 'date',
            'volume.status' => 'required|integer|min:0',
            'volume.price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
            'volume.isbn' => ['required', 'unique:volumes,isbn,' . $this->volume->id . ',id,series_id,' . $this->series->id, new Isbn()],
        ];
    }

    public function updated($property, $value)
    {
        if ($property == "volume.isbn") {
            $this->validateOnly($property);
            $isbn = IsbnHelpers::convertTo13($value);
            if (!empty($isbn)) {
                $this->volume->publish_date = IsbnHelpers::getPublishDateByIsbn($isbn) ?? '';
            }
        } else {
            $this->validateOnly($property);
        }
    }

    public function render()
    {
        return view('livewire.volumes.edit-volume')->extends('layouts.app')->section('content');;
    }

    public function save()
    {
        $isbn = IsbnHelpers::convertTo13($this->volume->isbn);
        if (!empty($isbn)) {
            $this->volume->isbn = $isbn;
        }
        if (!empty($this->volume->price)) {
            $this->volume->price = floatval(Str::replace(',', '.', $this->volume->price));
        }
        $this->validate();
        $this->volume->save();
        toastr()->addSuccess(__('Volumme :number has been updated', ['number' => $this->volume->number]));
        return redirect()->route('series.show', [$this->category, $this->series]);
    }

    public function delete()
    {
        $this->volume->delete();
        toastr()->addSuccess(__('Volumme :number has been deleted', ['number' => $this->volume->number]));
        return redirect()->route('series.show', [$this->category, $this->series]);
    }
}
