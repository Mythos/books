<?php

namespace App\Http\Livewire\Books;

use App\Models\Book;
use App\Models\Series;
use Http;
use Intervention\Validation\Rules\Isbn;
use Livewire\Component;
use Nicebooks\Isbn\Exception\InvalidIsbnException;
use Nicebooks\Isbn\Isbn as IsbnIsbn;
use Nicebooks\Isbn\IsbnTools;

class CreateBook extends Component
{
    public string $publish_date = '';
    public string $isbn = '';
    public int $status = 0;
    public Series $series;

    public function mount(Series $series)
    {
        $this->series = $series;
    }

    public function render()
    {
        return view('livewire.books.create-book')->extends('layouts.app')->section('content');
    }

    public function updated($name, $value)
    {
        if($name == "isbn") {
            $this->publish_date = $this->getPublishDateByIsbn($value);
        }
    }

    public function save()
    {
        $isbn = $this->isbn;
        try {
            if (!empty($isbn)) {
                $isbn = IsbnIsbn::of($isbn)->to13();
                $this->isbn = $isbn;
            }
        } catch (InvalidIsbnException $exception) {
        }
        $this->validate([
            'publish_date' => 'date',
            'status' => 'required|integer|min:0',
            'isbn' => ['required', 'unique:books,isbn,NULL,id,series_id,' . $this->series->id, new Isbn()],
        ]);
        $number = Book::whereSeriesId($this->series->id)->max('number') ?? 0;
        $book = new Book([
            'series_id' => $this->series->id,
            'number' => ++$number,
            'publish_date' => $this->publish_date,
            'isbn' => $this->isbn,
            'status' => $this->status
        ]);
        $book->save();
        toastr()->livewire()->addSuccess(__('Volumme :number has been created', ['number' => $number]));
        $this->resetExcept('series');
    }

    private function getPublishDateByIsbn($isbn){
        $tools = new IsbnTools();
        if($tools->isValidIsbn($isbn)){
            $isbn = IsbnIsbn::of($isbn)->to13();
            $response = Http::get('https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
            if($response['totalItems'] > 0) {
                return $response["items"][0]["volumeInfo"]["publishedDate"];
            }
        }
        return '';
    }
}
