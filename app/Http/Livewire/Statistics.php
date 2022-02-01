<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Statistics extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $volumesByStatusStatistics;

    public $seriesByPublisherStatistics;

    public function render()
    {
        $this->loadVolumesByStatusStatistics();
        $this->loadSeriesByPublisherStatistics();
        $unreadSeries = DB::table('series')
                            ->join('volumes', 'volumes.series_id', '=', 'series.id')
                            ->where('volumes.status', '=', 3)
                            ->select('series.name', DB::raw('count(*) as unread'))
                            ->groupBy('series.name')
                            ->orderByDesc('unread')
                            ->orderBy('series.name')
                            ->paginate(10, ['*'], 'unread');
        $mostReadSeries = DB::table('series')
                              ->join('volumes', 'volumes.series_id', '=', 'series.id')
                              ->where('volumes.status', '=', 4)
                              ->select('series.name', DB::raw('count(*) as `read`'))
                              ->groupBy('series.name')
                              ->orderByDesc('read')
                              ->orderBy('series.name')
                              ->paginate(10, ['*'], 'mostread');

        return view('livewire.statistics', [
            'unreadSeries' => $unreadSeries,
            'mostReadSeries' => $mostReadSeries,
        ])->extends('layouts.app')->section('content');
    }

    public function loadVolumesByStatusStatistics(): void
    {
        $data = DB::table('volumes')
                    ->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->get();
        $this->volumesByStatusStatistics = [
            __('New') => $data->where('status', '=', '0')->first()?->total ?? 0,
            __('Ordered') => $data->where('status', '=', '1')->first()?->total ?? 0,
            __('Shipped') => $data->where('status', '=', '2')->first()?->total ?? 0,
            __('Delivered') => $data->where('status', '=', '3')->first()?->total ?? 0,
            __('Read') => $data->where('status', '=', '4')->first()?->total ?? 0,
        ];
    }

    public function loadSeriesByPublisherStatistics(): void
    {
        $this->seriesByPublisherStatistics = DB::table('series')
                                                 ->join('publishers', 'series.publisher_id', '=', 'publishers.id')
                                                 ->select('publishers.name as publisher', DB::raw('count(*) as total'))
                                                 ->groupBy('publishers.name')
                                                 ->get()
                                                 ->pluck('total', 'publisher')
                                                 ->toArray();
    }
}
