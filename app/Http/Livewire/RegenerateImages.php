<?php

namespace App\Http\Livewire;

use App\Models\Series;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Component;

class RegenerateImages extends Component
{
    public function render()
    {
        return view('livewire.regenerate-images');
    }

    public function regenerate(): void
    {
        $series = Series::all();
        foreach ($series as $item) {
            $image = Image::make(Storage::disk('public')->get($item->image_path . '/cover.jpg'));
            Storage::disk('public')->put($item->image_path . '/cover_sfw.jpg', $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg'));
        }
        toastr()->livewire()->addSuccess(__('Images have been updated'));
    }
}
