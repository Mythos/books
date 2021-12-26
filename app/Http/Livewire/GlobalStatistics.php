<?php

namespace App\Http\Livewire;

use App\Models\Volume;
use Cache;
use Livewire\Component;

class GlobalStatistics extends Component
{
    public ?int $new;
    public ?int $ordered;
    public ?int $shipped;
    public ?int $delivered;
    public ?int $total;

    public function render()
    {
        $volumes = Cache::remember('volumes', config('cache.duration'), function () {
            return Volume::all();
        });
        $this->new = $volumes->where('status', '0')->count();
        $this->ordered = $volumes->where('status', '1')->count();
        $this->shipped = $volumes->where('status', '2')->count();
        $this->delivered = $volumes->where('status', '3')->count();
        $this->total = $volumes->count();
        return view('livewire.global-statistics');
    }
}
