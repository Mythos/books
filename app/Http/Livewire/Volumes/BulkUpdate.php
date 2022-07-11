<?php

namespace App\Http\Livewire\Volumes;

use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class BulkUpdate extends Component
{
    public Category $category;

    public Series $series;

    public Collection $volumes;

    public bool $selectAll = false;

    public array $selectedVolumes = [];

    public ?string $status = null;

    public ?string $price = null;

    protected $rules = [
        'selectall' => 'boolean',
        'status' => 'nullable|integer|min:0',
        'price' => 'nullable|regex:"^[0-9]{1,9}([,.][0-9]{1,2})?$"',
    ];

    public function mount(Category $category, Series $series): void
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function updatedSelectall($value): void
    {
        if ($value) {
            $this->selectedVolumes = $this->volumes->pluck('id')->toArray();
        } else {
            $this->selectedVolumes = [];
        }
    }

    public function render()
    {
        $this->volumes = Volume::whereSeriesId($this->series->id)->orderBy('number')->get();

        return view('livewire.volumes.bulk-update')->extends('layouts.app')->section('content');
    }

    public function save(): void
    {
        $volumes = $this->volumes->whereIn('id', $this->selectedVolumes);
        foreach ($volumes as $volume) {
            if ($this->status == null && ($this->price ?? '') == '') {
                continue;
            }
            if ($this->status != null) {
                $volume->status = $this->status;
            }
            if (($this->price ?? '') != '') {
                $volume->price = floatval(Str::replace(',', '.', $this->price));
            }
            $volume->save();
        }
        $this->reset(['selectAll', 'selectedVolumes']);
    }

    public function selectPublished(): void
    {
        $today = Carbon::today();
        $this->selectedVolumes = $this->volumes->where('publish_date', '<=', $today)->pluck('id')->toArray();
    }

    public function selectUnpublished(): void
    {
        $today = Carbon::today();
        $this->selectedVolumes = $this->volumes->filter(function ($volume) use ($today) {
            return empty($volume->publish_date) || $volume->publish_date > $today;
        })->pluck('id')->toArray();
    }
}
