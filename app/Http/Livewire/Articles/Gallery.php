<?php

namespace App\Http\Livewire\Articles;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;

class Gallery extends Component
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
        $articles = Article::whereCategoryId($this->category->id);
        if (!empty($this->search)) {
            $articles->where('name', 'like', '%' . $this->search . '%');
        }
        $this->articles = $articles->orderBy('status')->orderBy('name')->get();

        return view('livewire.articles.gallery');
    }

    public function filter($filter): void
    {
        $this->search = $filter;
    }
}
