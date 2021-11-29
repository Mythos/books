<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::with('series.books')->orderBy('name')->get();
        $upcoming = Book::with('series.category')->where('status', '!=', '2')->orderBy('publish_date')->get();
        return view('home')->with('categories', $categories)->with('upcoming', $upcoming);
    }
}
