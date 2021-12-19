<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Cache;

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
        $categories = Cache::remember('categories', 60 * 10, function () {
            return Category::all();
        });
        return view('home')->with('categories', $categories);
    }
}
