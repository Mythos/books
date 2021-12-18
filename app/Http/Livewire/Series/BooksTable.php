<?php

namespace App\Http\Livewire\Series;

use App\Models\Book;
use App\Models\Category;
use App\Models\Series;
use Http;
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
        $this->setStatus($id, 1);
    }

    public function delivered(int $id)
    {
        $this->setStatus($id, 2);
    }

    public function canceled(int $id)
    {
        $this->setStatus($id, 0);
    }

    public function refresh(int $id)
    {
        $book = Book::find($id);
        $publish_date = $this->getPublishDateByIsbn($book->isbn);
        if($book->publish_date != $publish_date) {
            $book->publish_date = $publish_date;
            $book->save();
            toastr()->livewire()->addInfo(__('Publish date of :name has been set to :date', ['name' => $book->series->name . ' ' . $book->number, 'date' => $publish_date]));
        }
        else {
            toastr()->livewire()->addSuccess(__('No changes have been found for :name', ['name' => $book->series->name . ' ' . $book->number]));
        }
    }

    private function getPublishDateByIsbn($isbn){
        $response = Http::get('https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
        if($response['totalItems'] > 0) {
            $date = $response["items"][0]["volumeInfo"]["publishedDate"];
            if(!empty($date)) {
                return date('Y-m-d', strtotime($date));
            }
        }
        return '';
    }

    private function setStatus(int $id, int $status)
    {
        $book = Book::find($id);
        $book->status = $status;
        $book->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $book->series->name . ' ' . $book->number]));
    }
}
