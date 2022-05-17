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

    public $ready = false;

    public function load(): void
    {
        $this->ready = true;
    }

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    protected $listeners = ['search' => 'filter'];

    public function render()
    {
        if ($this->ready) {
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
