<?php

namespace App\Http\Livewire\Articles;

use App\Helpers\ImageHelpers;
use App\Models\Article;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateArticle extends Component
{
    public Category $category;

    public Article $article;

    protected $rules = [
        'article.name' => 'required',
        'article.release_date' => 'date',
        'article.price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
        'article.status' => 'required|integer|min:0',
        'article.category_id' => 'required|exists:categories,id',
        'article.image_url' => 'nullable|url',
    ];

    public function updated($property, $value): void
    {
        $this->validateOnly($property);
    }

    public function mount(Category $category): void
    {
        $this->category = $category;
        $this->article = new Article([
            'status' => 0,
            'category_id' => $category->id,
        ]);
    }

    public function render()
    {
        return view('livewire.articles.create-article')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        if (!empty($this->article->price)) {
            $this->article->price = floatval(Str::replace(',', '.', $this->article->price));
        }
        $this->article->category_id = $this->category->id;
        try {
            $this->article->save();
            ImageHelpers::createAndSaveArticleImage($this->article->image_url, $this->article->image_path);
            toastr()->addSuccess(__(':name has been created', ['name' => $this->article->name]));

            return redirect()->route('home');
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->addError(__(':name could not be created', ['name' => $this->article->name]));
        }
    }
}
