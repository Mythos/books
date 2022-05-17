<?php

namespace App\Http\Livewire;

use Livewire\Component;

abstract class DeferredComponent extends Component
{
    public bool $loaded = false;

    public function load(): void
    {
        $this->loaded = true;
    }
}
