<?php

namespace App\Http\Livewire\Series;

use App\Models\Book;
use App\Models\Category;
use App\Models\Series;
use Livewire\Component;

class BooksTable extends Component
{
    public $books = [];
    public Series $series;
    public Category $category;

    public function mount(Category $category, Series $series)
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->books = Book::whereSeriesId($this->series->id)->orderBy('number')->get();
        return view('livewire.series.books-table');
    }

    public function ordered(int $id)
    {
        $book = Book::find($id);
        $book->status = 1;
        $book->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $book->series->name . ' ' . $book->number]));
    }
    public function delivered(int $id)
    {
        $book = Book::find($id);
        $book->status = 2;
        $book->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $book->series->name . ' ' . $book->number]));
    }
}
