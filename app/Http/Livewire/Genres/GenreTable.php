<?php

namespace App\Http\Livewire\Genres;

use App\Http\Livewire\DeferredLoading;
use App\Models\Genre;
use Livewire\Component;

class GenreTable extends Component
{
    use DeferredLoading;

    public $genres;

    public function render()
    {
        if ($this->loaded) {
            $this->genres = Genre::with('series')->orderBy('type')->orderBy('name')->get();
        } else {
            $this->genres = [];
        }

        return view('livewire.genres.genre-table')->extends('layouts.app')->section('content');
    }
}
