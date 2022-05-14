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
        ])->extends('layouts.app')->section('content');
    }

    private function getMostReadSeries()
    {
        return Series::with(['category', 'volumes'])
                       ->where('status', '<>', SeriesStatus::Canceled)
                       ->withCount([
                           'volumes as read_sum' => function ($query): void {
                               $query->select(DB::raw('SUM(CASE WHEN `status` = ' . VolumeStatus::Read . ' THEN 1 ELSE 0 END)'));
                           },
                       ])
                       ->whereHas('volumes', function (Builder $query): void {
                           $query->where('status', '=', VolumeStatus::Read);
                       })
                       ->orderByDesc('read_sum')
                       ->paginate(10, ['*'], 'mostread');
    }

    private function getUnreadSeries()
    {
        return Series::with(['category', 'volumes'])
                       ->where('status', '<>', SeriesStatus::Canceled)
                       ->withCount([
                           'volumes as unread_sum' => function ($query): void {
                               $query->select(DB::raw('SUM(CASE WHEN `status` = ' . VolumeStatus::Delivered . ' THEN 1 ELSE 0 END)'));
                           },
                       ])
                       ->whereHas('volumes', function (Builder $query): void {
                           $query->where('status', '=', VolumeStatus::Delivered);
                       })
                       ->orderByDesc('unread_sum')
                       ->paginate(10, ['*'], 'unread');
    }
}
