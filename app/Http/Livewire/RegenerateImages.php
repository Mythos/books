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

    public function regenerate()
    {
        $series = Series::all();
        foreach ($series as $item) {
            $image = Image::make(Storage::get('public/series/' . $item->id . '/cover.jpg'));
            Storage::put('public/series/' . $item->id . '/cover_sfw.jpg', $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg'));
        }
        toastr()->livewire()->addSuccess(__('Images have been updated'));
    }
}
