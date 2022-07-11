<?php

namespace App\Http\Livewire\Articles;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Gallery extends Component
{
    use WithPagination;

    public string $search = '';

    public Category $category;

    public int $total = 0;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['search' => 'filter'];

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function render()
    {
        $articles = Article::whereCategoryId($this->category->id);
        if (!empty($this->search)) {
            $articles->where('name', 'like', '%' . $this->search . '%');
        }
        $articles = $articles->orderBy('status')->orderBy('name');
        $this->total = $articles->count();
        if (!empty($this->category->page_size)) {
            $articles = $articles->paginate($this->category->page_size, ['*'], $this->category->slug);
        } else {
            $articles = $articles->get();
        }

        return view('livewire.articles.gallery', [
            'articles' => $articles,
        ]);
    }

    public function filter($filter): void
    {
        $this->search = $filter;
        $this->resetPage($this->category->slug);
    }
}
