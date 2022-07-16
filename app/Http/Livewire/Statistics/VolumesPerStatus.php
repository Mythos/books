<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\SeriesStatus;
use App\Constants\VolumeStatus;
use App\Models\Volume;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VolumesPerStatus extends Component
{
    public $volumesByStatusStatistics;

    public function render()
    {
        $volumes = Volume::join('series', 'volumes.series_id', '=', 'series.id');
        $volumes = $volumes->select([
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::NEW . ' AND series.status <> ' . SeriesStatus::CANCELED . ' then 1 else 0 end), 0) as new'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::ORDERED . ' AND series.status <> ' . SeriesStatus::CANCELED . ' then 1 else 0 end), 0) as ordered'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::SHIPPED . ' then 1 else 0 end), 0) as shipped'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::DELIVERED . ' then 1 else 0 end), 0) as delivered'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::READ . ' then 1 else 0 end), 0) as `read`'),
        ])->first();
        $volumes = json_decode(json_encode($volumes->toArray()), true);
        $this->volumesByStatusStatistics = [
            __('New') => $volumes['new'] ?? 0,
            __('Ordered') => $volumes['ordered'] ?? 0,
            __('Shipped') => $volumes['shipped'] ?? 0,
            __('Delivered') => $volumes['delivered'] ?? 0,
            __('Read') => $volumes['read'] ?? 0,
        ];

        return view('livewire.statistics.volumes-per-status');
    }
}
