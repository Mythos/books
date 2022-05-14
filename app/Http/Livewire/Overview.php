<?php

namespace App\Http\Livewire;

use App\Constants\SeriesStatus;
use App\Constants\VolumeStatus;
use App\Models\Volume;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Overview extends Component
{
    public $volumeStatistics = [];

    public $articleStatistics = [];

    public string $search;

    protected $listeners = [
        '$refresh',
        'search' => 'filter',
    ];

    public function render()
    {
        $this->volumeStatistics = $this->getVolumeStatistics();
        $this->articleStatistics = $this->getArticleStatistics();

        return view('livewire.overview');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }

    private function getVolumeStatistics()
    {
        $volumes = Volume::with(['series.publisher', 'series.genres']);

        if (!empty($this->search)) {
            $volumes->where('isbn', 'like', '%' . $this->search . '%')
                    ->orWhereHas('series', function ($query): void {
                        $query->where('name', 'like', '%' . $this->search . '%')
                              ->orWhereHas('publisher', function ($query): void {
                                  $query->where('name', 'like', '%' . $this->search . '%');
                              })
                              ->orWhereHas('genres', function ($query): void {
                                  $query->where('name', 'like', '%' . $this->search . '%');
                              });
                    });
        }
        $volumes = $volumes->join('series', 'volumes.series_id', '=', 'series.id')->WhereNotNull('isbn')->select([
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::New . ' AND series.status <> ' . SeriesStatus::Canceled . ' then 1 else 0 end), 0) as new'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::Ordered . ' AND series.status <> ' . SeriesStatus::Canceled . ' then 1 else 0 end), 0) as ordered'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::Shipped . ' then 1 else 0 end), 0) as shipped'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::Delivered . ' then 1 else 0 end), 0) as delivered'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::Read . ' then 1 else 0 end), 0) as `read`'),
            DB::raw('COALESCE(sum(case when volumes.status = ' . VolumeStatus::Delivered . ' OR volumes.status = ' . VolumeStatus::Read . ' then price else 0 end), 0) as price'),
            DB::raw('COALESCE(sum(case when series.status <> ' . SeriesStatus::Canceled . ' or volumes.status = ' . VolumeStatus::Delivered . ' or volumes.status = ' . VolumeStatus::Read . ' then 1 else 0 end), 0) as total'),
        ])->first();

        return json_decode(json_encode($volumes->toArray()), true);
    }

    private function getArticleStatistics()
    {
        $articleStatisticsQuery = DB::table('articles');
        if (!empty($this->search)) {
            $articleStatisticsQuery->where('name', 'like', '%' . $this->search . '%');
        }
        $articleStatisticsQuery = $articleStatisticsQuery->select([
            DB::raw('COALESCE(sum(case when status = 0 then 1 else 0 end), 0) as new'),
            DB::raw('COALESCE(sum(case when status = 1 then 1 else 0 end), 0) as ordered'),
            DB::raw('COALESCE(sum(case when status = 2 then 1 else 0 end), 0) as shipped'),
            DB::raw('COALESCE(sum(case when status = 3 then 1 else 0 end), 0) as delivered'),
            DB::raw('0 as `read`'),
            DB::raw('COALESCE(sum(case when status = 3 then price else 0 end), 0) as price'),
            DB::raw('count(*) as total'),
        ])->first();

        return json_decode(json_encode($articleStatisticsQuery), true);
    }
}
