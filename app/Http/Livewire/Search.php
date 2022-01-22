<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Search extends Component
{
    public string $search = '';

    public function render()
    {
        return view('livewire.search');
    }

    public function updated(): void
    {
        $this->emit('search', $this->search);
    }
}
