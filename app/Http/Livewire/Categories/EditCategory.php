<?php

namespace App\Http\Livewire\Categories;

use App\Models\Article;
use App\Models\Category;
use App\Models\GenreSeries;
use App\Models\Series;
use App\Models\Volume;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Storage;

class EditCategory extends Component
{
    use LivewireAlert;

    public Category $category;

    protected $rules = [
        'category.name' => 'required',
        'category.sort_index' => 'required|integer|min:0',
        'category.type' => 'required|integer|min:0',
        'category.page_size' => 'nullable|integer|min:0',
    ];

    protected $listeners = [
        'confirmedDelete',
    ];

    public function render()
    {
        return view('livewire.categories.edit-category')->extends('layouts.app')->section('content');
    }

    public function updated($property, $value): void
    {
        if ($property == 'category.page_size') {
            $this->category->page_size = !empty($value) ? $value : null;
        }
        $this->validateOnly($property);
    }

    public function save(): void
    {
        $this->validate();
        $this->category->save();
        toastr()->addSuccess(__(':name has been updated', ['name' => $this->category->name]));
    }

    public function delete(): void
    {
        $this->confirm(__('Are you sure you want to delete :name?', ['name' => $this->category->name]), [
            'confirmButtonText' => __('Delete'),
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'confirmedDelete',
        ]);
    }

    public function confirmedDelete()
    {
        if ($this->category->type == 0) {
            $this->deleteSeries();
        } elseif ($this->category->type == 1) {
            $this->deleteArticles();
        }
        $this->category->delete();
        toastr()->addSuccess(__(':name has been deleted', ['name' => $this->category->name]));

        return redirect()->route('home');
    }

    private function deleteSeries(): void
    {
        $series = Series::whereCategoryId($this->category->id)->get();
        foreach ($series as $s) {
            GenreSeries::whereSeriesId($s->id)->delete();
            Volume::whereSeriesId($s->id)->delete();
            $s->delete();
            Storage::disk('public')->deleteDirectory($s->image_path);
            Storage::disk('public')->deleteDirectory('thumbnails/' . $s->image_path);
        }
    }

    private function deleteArticles(): void
    {
        $articles = Article::whereCategoryId($this->category->id)->get();
        foreach ($articles as $article) {
            $article->delete();
            Storage::disk('public')->deleteDirectory($article->image_path);
        }
    }
}
