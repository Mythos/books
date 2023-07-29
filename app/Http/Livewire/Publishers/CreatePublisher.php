<?php

namespace App\Http\Livewire\Publishers;

use App\Helpers\ImageHelpers;
use App\Models\Publisher;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class CreatePublisher extends Component
{
    public Publisher $publisher;

    protected $rules = [
        'publisher.name' => 'required',
        'publisher.image_url' => 'nullable|url',
    ];

    public function updated($property, $value): void
    {
        $this->validateOnly($property);
    }

    public function mount(): void
    {
        $this->publisher = new Publisher();
    }

    public function render()
    {
        return view('livewire.publishers.create-publisher')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        if (!empty($this->series->default_price)) {
            $this->series->default_price = floatval(Str::replace(',', '.', $this->series->default_price));
        }
        try {
            $this->publisher->save();
            ImageHelpers::updatePublisherImage($this->publisher, true);
            toastr()->addSuccess(__(':name has been created', ['name' => $this->publisher->name]));

            return redirect()->route('publishers.index');
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->addError(__(':name could not be created', ['name' => $this->publisher->name]));
        }
    }
}
