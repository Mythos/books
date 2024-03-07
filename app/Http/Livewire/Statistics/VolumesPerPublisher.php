<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\VolumeStatus;
use App\Models\Volume;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VolumesPerPublisher extends Component
{
    public $volumesByPublisherStatistics;

    public function render()
    {
        $this->volumesByPublisherStatistics = Volume::join('series', 'volumes.series_id', '=', 'series.id')
                                                    ->join('publishers', 'series.publisher_id', '=', 'publishers.id')
                                                    ->where(function ($query): void {
                                                        $query->whereIn('volumes.status', [VolumeStatus::DELIVERED, VolumeStatus::READ]);
                                                    })
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
