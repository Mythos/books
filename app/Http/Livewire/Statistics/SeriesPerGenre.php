<?php

namespace App\Http\Livewire\Statistics;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SeriesPerGenre extends Component
{
    public $seriesByGenreStatistics;

    public function render()
    {
        $this->seriesByGenreStatistics = DB::table('series')
                                             ->join('genre_series', 'genre_series.series_id', '=', 'series.id')
                                             ->join('genres', 'genre_series.genre_id', '=', 'genres.id')
                                             ->where('series.status', '<>', '3')
                                             ->where('genres.type', '=', '1')
                                             ->select('genres.name as genre', DB::raw('count(*) as total'))
                                             ->groupBy('genres.name')
                                             ->orderByDesc('total')
                                             ->get()
                                             ->pluck('total', 'genre')
                                             ->toArray();

        return view('livewire.statistics.series-per-genre');
    }
}
