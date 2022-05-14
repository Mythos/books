<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\SeriesStatus;
use App\Constants\VolumeStatus;
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
                    ->where('series.status', '<>', SeriesStatus::Canceled)
                    ->select('volumes.status', DB::raw('count(*) as total'))
                    ->groupBy('volumes.status')
                    ->get();
        $this->volumesByStatusStatistics = [
            __('New') => $data->where('status', '=', VolumeStatus::New)->first()?->total ?? 0,
            __('Ordered') => $data->where('status', '=', VolumeStatus::Ordered)->first()?->total ?? 0,
            __('Shipped') => $data->where('status', '=', VolumeStatus::Shipped)->first()?->total ?? 0,
            __('Delivered') => $data->where('status', '=', VolumeStatus::Delivered)->first()?->total ?? 0,
            __('Read') => $data->where('status', '=', VolumeStatus::Read)->first()?->total ?? 0,
        ];

        return view('livewire.statistics.volumes-per-status');
    }
}
