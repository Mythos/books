<?php

namespace App\Http\Livewire\Publishers;

use App\Models\Publisher;
use Livewire\Component;

class PublisherTable extends Component
{
    public $publishers;

    public function render()
    {
        $this->publishers = Publisher::orderBy('name')->get();

        return view('livewire.publishers.publisher-table')->extends('layouts.app')->section('content');
    }
}
