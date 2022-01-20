<?php

namespace App\Http\Livewire\Articles;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;

class Gallery extends Component
{
    public $articles = [];

    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function render()
    {
        $this->articles = Article::whereCategoryId($this->category->id)->orderBy('status')->orderBy('name')->get();

        return view('livewire.articles.gallery');
    }
}
