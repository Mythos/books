<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GlobalStatistics extends Component
{
    public array $volumeStatistics = [];

    public array $articleStatistics = [];

    protected $listeners = [
        '$refresh',
    ];

    public function render()
    {
        $volumeStatistics = DB::table('volumes')->select([
            DB::raw('sum(case when status = 0 then 1 else 0 end) as new'),
            DB::raw('sum(case when status = 1 then 1 else 0 end) as ordered'),
            DB::raw('sum(case when status = 2 then 1 else 0 end) as shipped'),
            DB::raw('sum(case when status = 3 then 1 else 0 end) as delivered'),
            DB::raw('sum(case when status = 4 then 1 else 0 end) as `read`'),
            DB::raw('sum(case when status = 3 OR status = 4 then price else 0 end) as price'),
            DB::raw('count(*) as total'),
        ])->first();
        $articleStatistics = DB::table('articles')->select([
            DB::raw('sum(case when status = 0 then 1 else 0 end) as new'),
            DB::raw('sum(case when status = 1 then 1 else 0 end) as ordered'),
            DB::raw('sum(case when status = 2 then 1 else 0 end) as shipped'),
            DB::raw('sum(case when status = 3 then 1 else 0 end) as delivered'),
            DB::raw('sum(case when status = 3 then price else 0 end) as price'),
            DB::raw('count(*) as total'),
        ])->first();

        $this->volumeStatistics = json_decode(json_encode($volumeStatistics), true);
        $this->articleStatistics = json_decode(json_encode($articleStatistics), true);

        return view('livewire.global-statistics');
    }
}
