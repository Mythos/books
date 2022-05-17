<?php

namespace App\Http\Livewire\Articles;

use App\Http\Livewire\DeferredComponent;
use App\Models\Article;
use App\Models\Category;

class Gallery extends DeferredComponent
{
    public $articles = [];

    public string $search = '';

    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    protected $listeners = ['search' => 'filter'];

    public function render()
    {
        if ($this->loaded) {
            $this->articles = Article::whereCategoryId($this->category->id);
            if (!empty($this->search)) {
                $this->articles->where('name', 'like', '%' . $this->search . '%');
            }
            $this->articles = $this->articles->orderBy('status')->orderBy('name')->get();
        } else {
            $this->articles = [];
        }

        return view('livewire.articles.gallery');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }
}
