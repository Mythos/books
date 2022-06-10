<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\SeriesStatus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SeriesPerStatus extends Component
{
    public $seriesByStatusStatistics;

    public function render()
    {
        $data = DB::table('series')
                  ->select('series.status', DB::raw('count(*) as total'))
                  ->groupBy('series.status')
                  ->get();
        $this->seriesByStatusStatistics = [
            __('Announced') => $data->where('status', '=', SeriesStatus::ANNOUNCED)->first()?->total ?? 0,
            __('Ongoing') => $data->where('status', '=', SeriesStatus::ONGOING)->first()?->total ?? 0,
            __('Finished') => $data->where('status', '=', SeriesStatus::FINISHED)->first()?->total ?? 0,
            __('Canceled') => $data->where('status', '=', SeriesStatus::CANCELED)->first()?->total ?? 0,
            __('Paused') => $data->where('status', '=', SeriesStatus::PAUSED)->first()?->total ?? 0,
        ];

        return view('livewire.statistics.series-per-status');
    }
}
