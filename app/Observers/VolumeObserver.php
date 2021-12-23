<?php

namespace App\Observers;

use App\Models\Volume;
use Cache;

class VolumeObserver
{
    /**
     * Handle the Volume "created" event.
     *
     * @param  \App\Models\Volume  $volume
     * @return void
     */
    public function created(Volume $volume)
    {
        Cache::forget('volumes.' . $volume->series->id);
        Cache::forget('volumes.table.' . $volume->series->id);
        Cache::forget('series.' . $volume->series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Volume "updated" event.
     *
     * @param  \App\Models\Volume  $volume
     * @return void
     */
    public function updated(Volume $volume)
    {
        Cache::forget('volumes.' . $volume->series->id);
        Cache::forget('volumes.table.' . $volume->series->id);
        Cache::forget('series.' . $volume->series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Volume "deleted" event.
     *
     * @param  \App\Models\Volume  $volume
     * @return void
     */
    public function deleted(Volume $volume)
    {
        Cache::forget('volumes.' . $volume->series->id);
        Cache::forget('volumes.table.' . $volume->series->id);
        Cache::forget('series.' . $volume->series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Volume "restored" event.
     *
     * @param  \App\Models\Volume  $volume
     * @return void
     */
    public function restored(Volume $volume)
    {
        Cache::forget('volumes.' . $volume->series->id);
        Cache::forget('volumes.table.' . $volume->series->id);
        Cache::forget('series.' . $volume->series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Volume "force deleted" event.
     *
     * @param  \App\Models\Volume  $volume
     * @return void
     */
    public function forceDeleted(Volume $volume)
    {
        Cache::forget('volumes.' . $volume->series->id);
        Cache::forget('volumes.table.' . $volume->series->id);
        Cache::forget('series.' . $volume->series->category->id);
        Cache::forget('upcoming');
    }
}
