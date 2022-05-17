<?php

namespace App\Http\Livewire\Publishers;

use App\Http\Livewire\DeferredLoading;
use App\Models\Publisher;
use Livewire\Component;

class PublisherTable extends Component
{
    use DeferredLoading;

    public $publishers;

    public function render()
    {
        if ($this->loaded) {
            $this->publishers = Publisher::orderBy('name')->get();
        } else {
            $this->publishers = [];
        }

        return view('livewire.publishers.publisher-table')->extends('layouts.app')->section('content');
    }
}
