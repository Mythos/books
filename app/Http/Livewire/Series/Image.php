<?php

namespace App\Http\Livewire\Series;

use App\Models\Series;
use Illuminate\Support\Facades\Cache;
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
        $this->image = Cache::tags('images_session_' . session()->getId())->remember('image_session_' . session()->getId() . '_' . $this->series->id, config('cache.duration'), function () {
            $image = FacadesImage::make('storage/series/' . $this->series->id . '.jpg');
            if ($this->series->is_nsfw && !session('show_nsfw', false)) {
                $image = $image->pixelate(10)->blur(5);
            }
            return $image->encode('data-url')->__toString();
        });
    }
}
