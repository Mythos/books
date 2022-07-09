<?php

namespace App\Http\Livewire\Statistics;

use App\Constants\SeriesStatus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VolumesPerGenre extends Component
{
    public $volumesByGenreStatistics;

    public function render()
    {
        $this->volumesByGenreStatistics = DB::table('volumes')
                                             ->join('series', 'volumes.series_id', '=', 'series.id')
                                             ->join('genre_series', 'genre_series.series_id', '=', 'series.id')
                                             ->join('genres', 'genre_series.genre_id', '=', 'genres.id')
                                             ->where('series.status', '<>', SeriesStatus::CANCELED)
                                             ->where('genres.type', '=', '1')
                                             ->select('genres.name as genre', DB::raw('count(*) as total'))
                                             ->groupBy('genres.name')
                                             ->orderByDesc('total')
                                             ->get()
                                             ->pluck('total', 'genre')
                                             ->toArray();

        return view('livewire.statistics.volumes-per-genre');
    }
}
