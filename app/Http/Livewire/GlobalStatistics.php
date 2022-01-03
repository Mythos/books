<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GlobalStatistics extends Component
{
    public ?int $new;
    public ?int $ordered;
    public ?int $shipped;
    public ?int $delivered;
    public ?float $price;
    public ?int $total;

    protected $listeners = [
        '$refresh'
    ];

    public function render()
    {
        $statistics = DB::table("volumes")
            ->select([
                DB::raw("sum(case when status = 0 then 1 else 0 end) as new"),
                DB::raw("sum(case when status = 1 then 1 else 0 end) as ordered"),
                DB::raw("sum(case when status = 2 then 1 else 0 end) as shipped"),
                DB::raw("sum(case when status = 3 then 1 else 0 end) as delivered"),
                DB::raw("sum(case when status = 3 then price else 0 end) as price"),
                DB::raw("count(*) as total"),
            ])
            ->first();

        $this->new = $statistics->new;
        $this->ordered = $statistics->ordered;
        $this->shipped = $statistics->shipped;
        $this->delivered = $statistics->delivered;
        $this->price = $statistics->price;
        $this->total = $statistics->total;
        return view('livewire.global-statistics');
    }
}
