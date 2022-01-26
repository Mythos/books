<?php

namespace App\Http\Livewire\Publishers;

use App\Models\Publisher;
use App\Models\Series;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditPublisher extends Component
{
    use LivewireAlert;

    public Publisher $publisher;

    protected $rules = [
        'publisher.name' => 'required',
    ];

    protected $listeners = [
        'confirmedDelete',
    ];

    public function updated($property, $value): void
    {
        $this->validateOnly($property);
    }

    public function mount(Publisher $publisher): void
    {
        $this->publisher = $publisher;
    }

    public function render()
    {
        return view('livewire.publishers.edit-publisher')->extends('layouts.app')->section('content');
    }

    public function save(): void
    {
        $this->validate();

        $this->publisher->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $this->publisher->name]));
    }

    public function delete(): void
    {
        $this->confirm(__('Are you sure you want to delete :name?', ['name' => $this->publisher->name]), [
            'confirmButtonText' => __('Delete'),
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'confirmedDelete',
        ]);
    }

    public function confirmedDelete(): void
    {
        Series::wherePublisherId($this->publisher->id)->update(['publisher_id' => null]);
        $this->publisher->delete();
        toastr()->addSuccess(__(':name has been deleted', ['name' => $this->publisher->name]));
        redirect()->route('publishers.index');
    }
}
