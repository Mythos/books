<?php

namespace App\Http\Livewire;

use App\Helpers\ImageHelpers;
use App\Models\Article;
use App\Models\Publisher;
use App\Models\Series;
use Exception;
use Illuminate\Support\Facades\Log;
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
            try {
                ImageHelpers::createAndSaveArticleImage($article->image_url, $article->image_path);
            } catch (Exception $exception) {
                Log::error('Error while regenerating image for article ' . $article->name, ['exception' => $exception]);
            }
        }
        $series = Series::with('volumes')->whereNotNull('image_url')->orderBy('id')->get();
        foreach ($series as $item) {
            try {
                ImageHelpers::createAndSaveCoverImage($item->image_url, $item->image_path);
            } catch (Exception $exception) {
                Log::error('Error while regenerating image for series ' . $item->name, ['exception' => $exception]);
            }
            $volumes = $item->volumes->whereNotNull('image_url')->sortBy('id');
            foreach ($volumes as $volume) {
                try {
                    ImageHelpers::createAndSaveCoverImage($volume->image_url, $volume->image_path);
                } catch (Exception $exception) {
                    Log::error('Error while regenerating image for volume ' . $volume->isbn, ['exception' => $exception]);
                }
            }
        }
        $publishers = Publisher::whereNotNull('image_url')->orderBy('id')->get();
        foreach ($publishers as $item) {
            try {
                ImageHelpers::updatePublisherImage($item, true);
            } catch (Exception $exception) {
                Log::error('Error while regenerating image for publisher ' . $item->name, ['exception' => $exception]);
            }
        }
        toastr()->addSuccess(__('Images have been updated'));
    }
}
