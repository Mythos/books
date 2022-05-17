<?php

namespace App\Http\Livewire;

use App\Helpers\ImageHelpers;
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
        $series = Series::with('volumes')->whereNotNull('image_url')->orderBy('id')->get();
        foreach ($series as $item) {
            $this->createAndSaveImage($item);
            $volumes = $item->volumes->whereNotNull('image_url')->sortBy('id');
            foreach ($volumes as $volume) {
                $this->createAndSaveImage($volume);
            }
        }
        toastr()->addSuccess(__('Images have been updated'));
    }

    private function createAndSaveImage($item): void
    {
        $image = ImageHelpers::getImage($item->image_url);
        if (!empty($image)) {
            ImageHelpers::storePublicImage($image, $item->image_path . '/cover.jpg', true);
            $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
            ImageHelpers::storePublicImage($nsfwImage, $item->image_path . '/cover_sfw.jpg', true);
        }
    }
}
