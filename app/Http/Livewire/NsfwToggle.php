<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NsfwToggle extends Component
{
    public bool $show_nsfw;

    public function render()
    {
        $this->show_nsfw = session('show_nsfw') ?? false;

        return view('livewire.nsfw-toggle');
    }

    public function toggle()
    {
        $this->show_nsfw = !$this->show_nsfw;
        session()->put('show_nsfw', $this->show_nsfw);

        return redirect(request()->header('Referer'));
    }
}
