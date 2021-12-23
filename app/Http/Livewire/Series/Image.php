<?php

namespace App\Http\Livewire\Series;

use App\Models\Series;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as FacadesImage;
use Livewire\Component;

class Image extends Component
{
    public Series $series;
    public string $image;

    public function mount(Series $series)
    {
        $this->series = $series;
        $this->getImage();
    }

    public function render()
    {
        return view('livewire.series.image');
    }

    public function getImage()
    {
        $this->image = Cache::remember('image_series_' . $this->series->id . '_session_' . session()->getId(), config('cache.duration'), function () {
            $img = Storage::get('public/series/' . $this->series->id . '.jpg');
            $image = FacadesImage::make($img);
            if ($this->series->is_nsfw && !session('show_nsfw', false)) {
                $image = $image->pixelate(10)->blur(5);
            }
            return $image->encode('data-url')->__toString();
        });
    }
}
