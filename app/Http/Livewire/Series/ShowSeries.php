<?php

namespace App\Http\Livewire\Series;

use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Nicebooks\Isbn\Isbn;

class ShowSeries extends Component
{
    public Category $category;

    public Series $series;

    public Collection $volumes;

    public bool $enable_reordering = false;

    public int $new;

    public int $ordered;

    public int $shipped;

    public int $delivered;

    public int $read;

    public function mount(Category $category, Series $series): void
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->series = Series::find($this->series->id);
        $this->volumes = Volume::whereSeriesId($this->series->id)->orderBy('number')->get();
        $this->new = $this->volumes->where('status', '0')->count();
        $this->ordered = $this->volumes->where('status', '1')->count();
        $this->shipped = $this->volumes->where('status', '2')->count();
        $this->delivered = $this->volumes->where('status', '3')->count();
        $this->read = $this->volumes->where('status', '4')->count();

        return view('livewire.series.show-series')->extends('layouts.app')->section('content');
    }

    public function canceled(int $id): void
    {
        $this->setStatus($id, 0);
    }

    public function ordered(int $id): void
    {
        $this->setStatus($id, 1);
    }

    public function shipped(int $id): void
    {
        $this->setStatus($id, 2);
    }

    public function delivered(int $id): void
    {
        $this->setStatus($id, 3);
    }

    public function read(int $id): void
    {
        $this->setStatus($id, 4);
    }

    private function setStatus(int $id, int $status): void
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function toggle_reordering(): void
    {
        $this->enable_reordering = !$this->enable_reordering;
    }

    public function move_up(int $id): void
    {
        $volume = Volume::find($id);
        if ($volume->number <= 1) {
            return;
        }
        $predecessor = Volume::whereSeriesId($volume->series_id)->orderByDesc('number')->where('number', '<', $volume->number)->first();
        $volume->number = --$volume->number;
        $predecessor->number = ++$predecessor->number;

        $volume->save();
        $predecessor->save();

        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function move_down(int $id): void
    {
        $volume = Volume::find($id);
        if ($volume->number >= $this->volumes->max('number')) {
            return;
        }
        $successor = Volume::whereSeriesId($volume->series_id)->orderBy('number')->where('number', '>', $volume->number)->first();
        $volume->number = ++$volume->number;
        $successor->number = --$successor->number;

        $volume->save();
        $successor->save();

        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }

    public function update():void
    {
        if (empty($this->series->mangapassion_id)) {
            return;
        }
        $response = Http::get('https://api.manga-passion.de/editions/' . $this->series->mangapassion_id);
        if ($response->successful()) {
            $result = $response->json();
            if (empty($result)) {
                return;
            }
            if (!empty($result['title'])) {
                $this->series->name = $result['title'];
            }
            if (!empty($result['status'])) {
                if ($result['status'] == 1) {
                    $this->series->status = 1;
                } elseif ($result['status'] == 2) {
                    $this->series->status = 2;
                } else {
                    $this->series->status = 0;
                }
            }
            if (!empty($result['sources'])) {
                $sourceId = $result['sources'][0]['id'];
                $sourceResponse = Http::get('https://api.manga-passion.de/sources/' . $sourceId);
                if ($sourceResponse->successful()) {
                    $source = $sourceResponse->json();
                    if (!empty($source)) {
                        if (!empty($source['volumes'])) {
                            $this->series->total = $source['volumes'];
                        }
                    }
                }
            }

            $volumesResponse = Http::get('https://api.manga-passion.de/editions/' . $this->series->mangapassion_id . '/volumes?itemsPerPage=1&order[number]=asc');
            if ($volumesResponse->successful()) {
                $volumesResult = $volumesResponse->json();
                if (count($volumesResult) > 0) {
                    foreach ($volumesResult as $volumeResult) {
                        if (empty($volumeResult['price'])) {
                            continue;
                        }
                        $this->series->default_price = !empty($volumeResult['price']) ? floatval($volumeResult['price']) / 100.0 : 0;
                    }
                }
            }
            $this->series->save();
        }

        $this->createVolumes();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $this->series->name]));
    }

    private function createVolumes(): void
    {
        if (empty($this->series->mangapassion_id)) {
            return;
        }
        $maxDate = Volume::whereSeriesId($this->series->id)->max('publish_date');
        $url = 'https://api.manga-passion.de/editions/' . $this->series->mangapassion_id . '/volumes?itemsPerPage=500&order[number]=asc';
        if (!empty($maxDate)) {
            $date = new DateTime($maxDate);
            $url .= '&date[strictly_after]=' . $date->format('Y-m-d');
        }
        $response = Http::get($url);
        if ($response->successful()) {
            $result = $response->json();
            if (count($result) > 0) {
                foreach ($result as $volumeResult) {
                    if (empty($volumeResult['isbn13'])) {
                        continue;
                    }
                    $publish_date = !empty($volumeResult['date']) ? new DateTime($volumeResult['date']) : null;
                    $volume = new Volume([
                        'series_id' => $this->series->id,
                        'isbn' => Isbn::of($volumeResult['isbn13'])->to13(),
                        'number' => $volumeResult['number'],
                        'publish_date' => !empty($publish_date) ? $publish_date->format('Y-m-d') : null,
                        'price' => !empty($volumeResult['price']) ? floatval($volumeResult['price']) / 100.0 : 0,
                        'status' => $this->series->subscription_active,
                    ]);
                    $volume->save();
                }
            }
        }
    }
}
