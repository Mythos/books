<?php

namespace App\Http\Livewire;

use App\Models\Volume;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistics extends Component
{
    public $volumesByStatusStatistics;

    public $seriesByPublisherStatistics;

    public function render()
    {
        $this->loadVolumesByStatusStatistics();
        $this->loadSeriesByPublisherStatistics();

        return view('livewire.statistics')->extends('layouts.app')->section('content');
    }

    public function loadVolumesByStatusStatistics(): void
    {
        $data = DB::table('volumes')
                           ->select('status', DB::raw('count(*) as total'))
                           ->groupBy('status')
                           ->get();
        $this->volumesByStatusStatistics = [
            __('New') => $data->where('status', '=', '0')->first()?->total ?? 0,
            __('Ordered') => $data->where('status', '=', '1')->first()?->total ?? 0,
            __('Shipped') => $data->where('status', '=', '2')->first()?->total ?? 0,
            __('Delivered') => $data->where('status', '=', '3')->first()?->total ?? 0,
            __('Read') => $data->where('status', '=', '4')->first()?->total ?? 0,
        ];
    }

    public function loadSeriesByPublisherStatistics(): void
    {
        $this->seriesByPublisherStatistics = DB::table('series')
                           ->join('publishers', 'series.publisher_id', '=', 'publishers.id')
                           ->select('publishers.name as publisher', DB::raw('count(*) as total'))
                           ->groupBy('publishers.name')
                           ->get()
                           ->pluck('total', 'publisher')
                           ->toArray();
    }
}
