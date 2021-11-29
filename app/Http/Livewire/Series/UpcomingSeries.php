<?php

namespace App\Http\Livewire\Series;

use App\Models\Book;
use Livewire\Component;

class UpcomingSeries extends Component
{
    public $upcoming;

    public function render()
    {
        $this->upcoming = Book::with('series.category')->where('status', '!=', '2')->orderBy('publish_date')->get();
        return view('livewire.series.upcoming-series');
    }
    public function ordered(int $id)
    {
        $book = Book::find($id);
        $book->status = 1;
        $book->save();
    }
    public function delivered(int $id)
    {
        $book = Book::find($id);
        $book->status = 2;
        $book->save();
    }
}
