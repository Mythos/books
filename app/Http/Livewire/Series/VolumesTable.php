<?php

namespace App\Http\Livewire\Series;

use App\Models\Volume;
use App\Models\Category;
use App\Models\Series;
use Http;
use Livewire\Component;

class VolumesTable extends Component
{
    public $volumes = [];
    public Series $series;
    public Category $category;

    public function mount(Category $category, Series $series)
    {
        $this->category = $category;
        $this->series = $series;
    }

    public function render()
    {
        $this->volumes = Volume::whereSeriesId($this->series->id)->orderBy('number')->get();
        return view('livewire.series.volumes-table');
    }

    public function ordered(int $id)
    {
        $this->setStatus($id, 1);
    }

    public function delivered(int $id)
    {
        $this->setStatus($id, 2);
    }

    public function canceled(int $id)
    {
        $this->setStatus($id, 0);
    }

    public function refresh(int $id)
    {
        $volume = Volume::find($id);
        $publish_date = $this->getPublishDateByIsbn($volume->isbn);
        if($volume->publish_date != $publish_date) {
            $volume->publish_date = $publish_date;
            $volume->save();
            toastr()->livewire()->addInfo(__('Publish date of :name has been set to :date', ['name' => $volume->series->name . ' ' . $volume->number, 'date' => $publish_date]));
        }
        else {
            toastr()->livewire()->addSuccess(__('No changes have been found for :name', ['name' => $volume->series->name . ' ' . $volume->number]));
        }
    }

    private function getPublishDateByIsbn($isbn){
        $response = Http::get('https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
        if($response['totalItems'] > 0) {
            $date = $response["items"][0]["volumeInfo"]["publishedDate"];
            if(!empty($date)) {
                return date('Y-m-d', strtotime($date));
            }
        }
        return '';
    }

    private function setStatus(int $id, int $status)
    {
        $volume = Volume::find($id);
        $volume->status = $status;
        $volume->save();
        toastr()->livewire()->addSuccess(__(':name has been updated', ['name' => $volume->series->name . ' ' . $volume->number]));
    }
}
