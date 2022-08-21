<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\VolumeStatus;
use App\Models\Series;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SeriesPerGenre extends Component
{
    public $seriesByGenreStatistics;

    public function render()
    {
        $this->seriesByGenreStatistics = Series::join('genre_series', 'genre_series.series_id', '=', 'series.id')
                                               ->join('genres', 'genre_series.genre_id', '=', 'genres.id')
                                               ->whereHas('volumes', function ($query): void {
                                                   $query->WhereIn('status', [VolumeStatus::ORDERED, VolumeStatus::SHIPPED, VolumeStatus::DELIVERED, VolumeStatus::READ]);
                                               })
                                               ->where('genres.type', '=', '1')
                                               ->select('genres.name as genre', DB::raw('count(*) as total'))
                                               ->groupBy('genres.name')
                                               ->orderByDesc('total')
                                               ->orderBy('genres.name')
                                               ->get()
                                               ->pluck('total', 'genre')
                                               ->toArray();

        return view('livewire.statistics.series-per-genre');
    }
}
