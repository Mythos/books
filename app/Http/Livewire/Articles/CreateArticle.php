<?php

namespace App\Http\Livewire\Articles;

use App\Models\Article;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as FacadesImage;
use Intervention\Image\Image;
use Livewire\Component;

class CreateArticle extends Component
{
    public Category $category;

    public Article $article;

    public string $image_url = '';

    protected $rules = [
        'article.name' => 'required',
        'article.release_date' => 'date',
        'article.price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
        'article.status' => 'required|integer|min:0',
        'article.category_id' => 'required|exists:categories,id',
        'image_url' => 'required|url',
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
            $image = $this->getImage();
            $this->article->save();
            $this->storeImages($image);
            toastr()->addSuccess(__(':name has been created', ['name' => $this->article->name]));

            return redirect()->route('home');
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->livewire()->addError(__(':name could not be created', ['name' => $this->article->name]));
        }
    }

    private function getImage(): ?Image
    {
        if (empty($this->image_url)) {
            return null;
        }
        $image = FacadesImage::make($this->image_url)->resize(null, 400, function ($constraint): void {
            $constraint->aspectRatio();
        })->encode('jpg');

        return $image;
    }

    private function storeImages($image): void
    {
        if (empty($image)) {
            return;
        }
        Storage::disk('public')->put('articles/' . $this->article->id . '/image.jpg', $image);
    }
}
