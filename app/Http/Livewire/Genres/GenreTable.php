<?php

namespace App\Http\Livewire\Genres;

use App\Models\Genre;
use Livewire\Component;

class GenreTable extends Component
{
    public $genres;

    public function render()
    {
        $this->genres = Genre::with('series:id')->orderBy('type')->orderBy('name')->get();

        return view('livewire.genres.genre-table')->extends('layouts.app')->section('content');
    }
}
