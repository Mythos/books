<?php

namespace App\Http\Livewire;

use App\Helpers\ImageHelpers;
use App\Models\Article;
use App\Models\Series;
use Livewire\Component;

class RegenerateImages extends Component
{
    public function render()
    {
        return view('livewire.regenerate-images');
    }

    public function regenerate(): void
    {
        $articles = Article::whereNotNull('image_url')->orderBy('id')->get();
        foreach ($articles as $article) {
            ImageHelpers::createAndSaveArticleImage($article->image_url, $article->image_path);
        }
        $series = Series::with('volumes')->whereNotNull('image_url')->orderBy('id')->get();
        foreach ($series as $item) {
            ImageHelpers::createAndSaveCoverImage($item->image_url, $item->image_path);
            $volumes = $item->volumes->whereNotNull('image_url')->sortBy('id');
            foreach ($volumes as $volume) {
                ImageHelpers::createAndSaveCoverImage($volume->image_url, $volume->image_path);
            }
        }
        toastr()->addSuccess(__('Images have been updated'));
    }
}
