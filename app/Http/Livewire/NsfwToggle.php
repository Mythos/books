<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Cache;
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
        Cache::tags('images_session_' . session()->getId())->flush();
        return redirect(request()->header('Referer'));
    }
}
