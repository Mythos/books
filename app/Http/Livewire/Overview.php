<?php

namespace App\Http\Livewire;

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
        $volumes = $volumes->WhereNotNull('isbn')->select([
            DB::raw('COALESCE(sum(case when volumes.status = 0 then 1 else 0 end), 0) as new'),
            DB::raw('COALESCE(sum(case when volumes.status = 1 then 1 else 0 end), 0) as ordered'),
            DB::raw('COALESCE(sum(case when volumes.status = 2 then 1 else 0 end), 0) as shipped'),
            DB::raw('COALESCE(sum(case when volumes.status = 3 then 1 else 0 end), 0) as delivered'),
            DB::raw('COALESCE(sum(case when volumes.status = 4 then 1 else 0 end), 0) as `read`'),
            DB::raw('COALESCE(sum(case when volumes.status = 3 OR volumes.status = 4 then price else 0 end), 0) as price'),
            DB::raw('count(*) as total'),
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
