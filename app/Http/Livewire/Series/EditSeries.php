<?php

namespace App\Http\Livewire\Series;

use App\Helpers\ImageHelpers;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Volume;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Storage;

class EditSeries extends Component
{
    use LivewireAlert;

    public $publishers;

    public Category $category;

    public Series $series;

    public string $image_url = '';

    protected $rules = [
        'series.name' => 'required',
        'series.status' => 'required|integer|min:0',
        'series.total' => 'nullable|integer|min:1',
        'series.category_id' => 'required|exists:categories,id',
        'series.is_nsfw' => 'boolean',
        'series.default_price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
        'series.publisher_id' => 'nullable|exists:publishers,id',
        'series.subscription_active' => 'boolean',
        'series.mangapassion_id' => 'nullable|integer',
        'image_url' => 'url',
    ];

    protected $listeners = [
        'confirmedDelete',
    ];

    public function updated($property, $value): void
    {
        if ($property == 'series.total' && empty($value)) {
            $this->series->total = null;
        }
        if ($property == 'series.publisher_id' && empty($value)) {
            $this->series->publisher_id = null;
        }
        $this->validateOnly($property);
    }

    public function mount(Category $category, Series $series): void
    {
        $this->publishers = Publisher::orderBy('name')->get();
        $this->series = $series;
    }

    public function render()
    {
        return view('livewire.series.edit-series')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        if (!empty($this->series->default_price)) {
            $this->series->default_price = floatval(Str::replace(',', '.', $this->series->default_price));
        }
        try {
            $image = ImageHelpers::getImage($this->image_url);
            $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
            $this->series->save();
            ImageHelpers::storePublicImage($image, $this->series->image_path . '/cover.jpg');
            ImageHelpers::storePublicImage($nsfwImage, $this->series->image_path . '/cover_sfw.jpg');
            $this->updatePrices();
            $this->updateStatuses();
            toastr()->addSuccess(__(':name has been updated', ['name' => $this->series->name]));

            return redirect()->route('series.show', [$this->category, $this->series]);
        } catch (Exception $exception) {
            Log::error($exception);
            toastr()->livewire()->addError(__(':name could not be updated', ['name' => $this->series->name]));
        }
    }

    public function delete(): void
    {
        $this->confirm(__('Are you sure you want to delete :name?', ['name' => $this->series->name]), [
            'confirmButtonText' => __('Delete'),
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'confirmedDelete',
        ]);
    }

    public function confirmedDelete(): void
    {
        Volume::whereSeriesId($this->series->id)->delete();
        $this->series->delete();
        Storage::disk('public')->deleteDirectory($this->series->image_path);
        toastr()->addSuccess(__(':name has been deleted', ['name' => $this->series->name]));
        redirect()->route('categories.show', [$this->category]);
    }

    private function updatePrices(): void
    {
        if (!empty($this->series->default_price) && $this->series->default_price > 0) {
            Volume::whereSeriesId($this->series->id)->whereNull('price')->update(['price' => $this->series->default_price]);
        }
    }

    private function updateStatuses(): void
    {
        if ($this->series->subscription_active) {
            Volume::whereSeriesId($this->series->id)->where('status', '=', '0')->update(['status' => 1]);
        }
    }
}
