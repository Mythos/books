<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\SeriesStatus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SeriesPerPublisher extends Component
{
    public $seriesByPublisherStatistics;

    public function render()
    {
        $this->seriesByPublisherStatistics = DB::table('series')
                                                 ->join('publishers', 'series.publisher_id', '=', 'publishers.id')
                                                 ->where('series.status', '<>', SeriesStatus::Canceled)
                                                 ->select('publishers.name as publisher', DB::raw('count(*) as total'))
                                                 ->groupBy('publishers.name')
                                                 ->orderByDesc('total')
                                                 ->orderBy('publishers.name')
                                                 ->get()
                                                 ->pluck('total', 'publisher')
                                                 ->toArray();

        return view('livewire.statistics.series-per-publisher');
    }
}
