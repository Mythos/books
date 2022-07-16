<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\VolumeStatus;
use App\Models\Series;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SeriesPerPublisher extends Component
{
    public $seriesByPublisherStatistics;

    public function render()
    {
        $this->seriesByPublisherStatistics = Series::join('publishers', 'series.publisher_id', '=', 'publishers.id')
                                                   ->whereHas('volumes', function ($query): void {
                                                       $query->WhereIn('status', [VolumeStatus::ORDERED, VolumeStatus::SHIPPED, VolumeStatus::DELIVERED, VolumeStatus::READ]);
                                                   })
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
