<?php

namespace App\Http\Livewire\Statistics;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VolumesPerStatus extends Component
{
    public $volumesByStatusStatistics;

    public function render()
    {
        $data = DB::table('volumes')
                    ->join('series', 'volumes.series_id', '=', 'series.id')
                    ->whereNotNull('volumes.isbn')
                    ->where('series.status', '<>', '3')
                    ->select('volumes.status', DB::raw('count(*) as total'))
                    ->groupBy('volumes.status')
                    ->get();
        $this->volumesByStatusStatistics = [
            __('New') => $data->where('status', '=', '0')->first()?->total ?? 0,
            __('Ordered') => $data->where('status', '=', '1')->first()?->total ?? 0,
            __('Shipped') => $data->where('status', '=', '2')->first()?->total ?? 0,
            __('Delivered') => $data->where('status', '=', '3')->first()?->total ?? 0,
            __('Read') => $data->where('status', '=', '4')->first()?->total ?? 0,
        ];

        return view('livewire.statistics.volumes-per-status');
    }
}
