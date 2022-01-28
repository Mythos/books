<?php

namespace App\Http\Livewire\Publishers;

use App\Models\Publisher;
use Livewire\Component;

class CreatePublisher extends Component
{
    public Publisher $publisher;

    protected $rules = [
        'publisher.name' => 'required',
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

        $this->publisher->save();
        toastr()->addSuccess(__(':name has been created', ['name' => $this->publisher->name]));

        return redirect()->route('publishers.index');
    }
}
