<?php

namespace App\Http\Livewire\Articles;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;

class ShowArticle extends Component
{
    public Category $category;

    public Article $article;

    public function mount(Category $category, Article $article): void
    {
        $this->category = $category;
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.articles.show-article')->extends('layouts.app')->section('content');
    }
}
