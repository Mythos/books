<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\SeriesStatus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VolumesPerPublisher extends Component
{
    public $volumesByPublisherStatistics;

    public function render()
    {
        $this->volumesByPublisherStatistics = DB::table('volumes')
                                                 ->join('series', 'volumes.series_id', '=', 'series.id')
                                                 ->join('publishers', 'series.publisher_id', '=', 'publishers.id')
                                                 ->where('series.status', '<>', SeriesStatus::CANCELED)
                                                 ->select('publishers.name as publisher', DB::raw('count(*) as total'))
                                                 ->groupBy('publishers.name')
                                                 ->orderByDesc('total')
                                                 ->orderBy('publishers.name')
                                                 ->get()
                                                 ->pluck('total', 'publisher')
                                                 ->toArray();

        return view('livewire.statistics.volumes-per-publisher');
    }
}
