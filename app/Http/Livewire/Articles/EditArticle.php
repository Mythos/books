<?php

namespace App\Http\Livewire\Articles;

use App\Helpers\ImageHelpers;
use App\Models\Article;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditArticle extends Component
{
    use LivewireAlert;

    public Category $category;

    public Article $article;

    public string $image_url = '';

    protected $rules = [
        'article.name' => 'required',
        'article.release_date' => 'date',
        'article.price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
        'article.status' => 'required|integer|min:0',
        'article.category_id' => 'required|exists:categories,id',
        'image_url' => 'url',
    ];

    protected $listeners = [
        'confirmedDelete',
    ];

    public function updated($property, $value): void
    {
        $this->validateOnly($property);
    }

    public function mount(Category $category, Article $article): void
    {
        $this->category = $category;
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.articles.edit-article')->extends('layouts.app')->section('content');
    }

    public function save(): void
    {
        $this->validate();
        if (!empty($this->article->price)) {
            $this->article->price = floatval(Str::replace(',', '.', $this->article->price));
        }
        try {
            $image = ImageHelpers::getImage($this->image_url);
            $this->article->save();
            ImageHelpers::storePublicImage($image, $this->article->image_path . '/image.jpg', true);
            toastr()->addSuccess(__(':name has been updated', ['name' => $this->article->name]));
            $this->reset(['image_url']);
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->addError(__(':name could not be updated', ['name' => $this->article->name]));
        }
    }

    public function delete(): void
    {
        $this->confirm(__('Are you sure you want to delete :name?', ['name' => $this->article->name]), [
            'confirmButtonText' => __('Delete'),
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'confirmedDelete',
        ]);
    }

    public function confirmedDelete(): void
    {
        $this->article->delete();
        Storage::disk('public')->deleteDirectory($this->article->image_path);
        toastr()->addSuccess(__(':name has been deleted', ['name' => $this->article->name]));
        redirect()->route('categories.show', [$this->category]);
    }
}
