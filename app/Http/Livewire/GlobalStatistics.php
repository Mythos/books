<?php

namespace App\Http\Livewire;

use App\Models\Volume;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GlobalStatistics extends Component
{
    public array $volumeStatistics = [];

    public array $articleStatistics = [];

    public string $search;

    protected $listeners = [
        '$refresh',
        'search' => 'filter',
    ];

    public function render()
    {
        $volumes = DB::table('volumes')
                   ->join('series', 'volumes.series_id', '=', 'series.id')
                   ->leftJoin('publishers', 'series.publisher_id', '=', 'publishers.id');
        if (!empty($this->search)) {
            $volumes->where('isbn', 'like', '%' . $this->search . '%')
            ->orWhere('series.name', 'like', '%' . $this->search . '%')
            ->orWhere('publishers.name', 'like', '%' . $this->search . '%');
        }
        $volumeStatistics = $volumes->select([
            DB::raw('COALESCE(sum(case when volumes.status = 0 then 1 else 0 end), 0) as new'),
            DB::raw('COALESCE(sum(case when volumes.status = 1 then 1 else 0 end), 0) as ordered'),
            DB::raw('COALESCE(sum(case when volumes.status = 2 then 1 else 0 end), 0) as shipped'),
            DB::raw('COALESCE(sum(case when volumes.status = 3 then 1 else 0 end), 0) as delivered'),
            DB::raw('COALESCE(sum(case when volumes.status = 4 then 1 else 0 end), 0) as `read`'),
            DB::raw('COALESCE(sum(case when volumes.status = 3 OR volumes.status = 4 then price else 0 end), 0) as price'),
            DB::raw('count(*) as total'),
        ]);

        $articles = DB::table('articles');
        if (!empty($this->search)) {
            $articles->where('name', 'like', '%' . $this->search . '%');
        }
        $articleStatistics = $articles->select([
            DB::raw('COALESCE(sum(case when status = 0 then 1 else 0 end), 0) as new'),
            DB::raw('COALESCE(sum(case when status = 1 then 1 else 0 end), 0) as ordered'),
            DB::raw('COALESCE(sum(case when status = 2 then 1 else 0 end), 0) as shipped'),
            DB::raw('COALESCE(sum(case when status = 3 then 1 else 0 end), 0) as delivered'),
            DB::raw('0 as `read`'),
            DB::raw('COALESCE(sum(case when status = 3 then price else 0 end), 0) as price'),
            DB::raw('count(*) as total'),
        ]);
        $statistics = $volumeStatistics->union($articleStatistics)->get();
        $this->volumeStatistics = json_decode(json_encode($statistics[0]), true);
        $this->articleStatistics = json_decode(json_encode($statistics[1]), true);

        return view('livewire.global-statistics');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }
}
