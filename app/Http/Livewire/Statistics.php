<?php

namespace App\Http\Livewire;

use App\Models\Series;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Statistics extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $volumesByStatusStatistics;

    public $seriesByPublisherStatistics;

    public $seriesByGenreStatistics;

    public function render()
    {
        $this->loadVolumesByStatusStatistics();
        $this->loadSeriesByPublisherStatistics();
        $this->loadSeriesByGenre();

        return view('livewire.statistics', [
            'unreadSeries' => $this->getUnreadSeries(),
            'mostReadSeries' => $this->getMostReadSeries(),
        ])->extends('layouts.app')->section('content');
    }

    private function loadVolumesByStatusStatistics(): void
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

    private function loadSeriesByPublisherStatistics(): void
    {
        $this->seriesByPublisherStatistics = DB::table('series')
                                                 ->join('publishers', 'series.publisher_id', '=', 'publishers.id')
                                                 ->select('publishers.name as publisher', DB::raw('count(*) as total'))
                                                 ->groupBy('publishers.name')
                                                 ->orderBy('publishers.name')
                                                 ->get()
                                                 ->pluck('total', 'publisher')
                                                 ->toArray();
    }

    private function loadSeriesByGenre(): void
    {
        $this->seriesByGenreStatistics = DB::table('series')
                                                 ->join('genre_series', 'genre_series.series_id', '=', 'series.id')
                                                 ->join('genres', 'genre_series.genre_id', '=', 'genres.id')
                                                 ->where('genres.type', '=', '1')
                                                 ->select('genres.name as genre', DB::raw('count(*) as total'))
                                                 ->groupBy('genres.name')
                                                 ->orderByDesc('total')
                                                 ->get()
                                                 ->pluck('total', 'genre')
                                                 ->toArray();
    }

    private function getMostReadSeries()
    {
        return Series::with(['category', 'volumes'])
                       ->withCount([
                           'volumes as read_sum' => function ($query): void {
                               $query->select(DB::raw('SUM(CASE WHEN `status` = 4 THEN 1 ELSE 0 END)'));
                           },
                       ])
                       ->whereHas('volumes', function (Builder $query): void {
                           $query->where('status', '=', '4');
                       })
                       ->orderByDesc('read_sum')
                       ->paginate(10, ['*'], 'mostread');
    }

    private function getUnreadSeries()
    {
        return Series::with(['category', 'volumes'])
                       ->withCount([
                           'volumes as unread_sum' => function ($query): void {
                               $query->select(DB::raw('SUM(CASE WHEN `status` = 3 THEN 1 ELSE 0 END)'));
                           },
                       ])
                       ->whereHas('volumes', function (Builder $query): void {
                           $query->where('status', '=', '3');
                       })
                       ->orderByDesc('unread_sum')
                       ->paginate(10, ['*'], 'unread');
    }
}
