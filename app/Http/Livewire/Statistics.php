<?php

namespace App\Http\Livewire;

use App\Constants\SeriesStatus;
use App\Constants\VolumeStatus;
use App\Models\Series;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Statistics extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.statistics', [
            'unreadSeries' => $this->getUnreadSeries(),
            'mostReadSeries' => $this->getMostReadSeries(),
            'mostValuableSeries' => $this->getMostValuableSeries(),
        ])->extends('layouts.app')->section('content');
    }

    private function getMostReadSeries()
    {
        return Series::with(['category', 'volumes'])
                       ->where('status', '<>', SeriesStatus::CANCELED)
                       ->withCount([
                           'volumes as read_sum' => function ($query): void {
                               $query->select(DB::raw('SUM(CASE WHEN `status` = ' . VolumeStatus::READ . ' THEN 1 ELSE 0 END)'));
                           },
                       ])
                       ->whereHas('volumes', function (Builder $query): void {
                           $query->where('status', '=', VolumeStatus::READ);
                       })
                       ->orderByDesc('read_sum')
                       ->paginate(10, ['*'], 'mostread');
    }

    private function getMostValuableSeries()
    {
        return Series::with(['category', 'volumes'])
                       ->whereHas('volumes', function (Builder $query): void {
                           $query->whereIn('status', [VolumeStatus::DELIVERED, VolumeStatus::READ]);
                       })
                       ->withSum(['volumes' => function ($query): void {
                           $query->whereIn('status', [VolumeStatus::DELIVERED, VolumeStatus::READ]);
                       }], 'price')
                       ->orderByDesc('volumes_sum_price')
                       ->paginate(10, ['*'], 'mostvaluable');
    }

    private function getUnreadSeries()
    {
        return Series::with(['category', 'volumes'])
                       ->where('status', '<>', SeriesStatus::CANCELED)
                       ->withCount([
                           'volumes as unread_sum' => function ($query): void {
                               $query->select(DB::raw('SUM(CASE WHEN `status` = ' . VolumeStatus::DELIVERED . ' THEN 1 ELSE 0 END)'));
                           },
                       ])
                       ->whereHas('volumes', function (Builder $query): void {
                           $query->where('status', '=', VolumeStatus::DELIVERED);
                       })
                       ->orderByDesc('unread_sum')
                       ->paginate(10, ['*'], 'unread');
    }
}
