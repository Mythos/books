<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Administration extends Component
{
    public function render()
    {
        return view('livewire.administration')->extends('layouts.app')->section('content');
    }
}
