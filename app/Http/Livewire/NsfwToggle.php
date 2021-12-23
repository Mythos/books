<?php

namespace App\Http\Livewire;

use App\Models\Series;
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
        $seriesIds = Series::whereIsNsfw(true)->pluck('id')->all();
        foreach ($seriesIds as $seriesId) {
            Cache::forget('image_series_' . $seriesId . '_session_' . session()->getId());
        }
        return redirect(request()->header('Referer'));
    }
}
