<?php

namespace App\Http\Livewire;

trait DeferredLoading
{
    public bool $loaded = false;

    public function load(): void
    {
        $this->loaded = true;
    }
}
