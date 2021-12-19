<?php

namespace App\Observers;

use App\Models\Series;
use Cache;

class SeriesObserver
{
    /**
     * Handle the Series "created" event.
     *
     * @param  \App\Models\Series  $series
     * @return void
     */
    public function created(Series $series)
    {
        Cache::forget('series.' . $series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Series "updated" event.
     *
     * @param  \App\Models\Series  $series
     * @return void
     */
    public function updated(Series $series)
    {
        Cache::forget('series.' . $series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Series "deleted" event.
     *
     * @param  \App\Models\Series  $series
     * @return void
     */
    public function deleted(Series $series)
    {
        Cache::forget('series.' . $series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Series "restored" event.
     *
     * @param  \App\Models\Series  $series
     * @return void
     */
    public function restored(Series $series)
    {
        Cache::forget('series.' . $series->category->id);
        Cache::forget('upcoming');
    }

    /**
     * Handle the Series "force deleted" event.
     *
     * @param  \App\Models\Series  $series
     * @return void
     */
    public function forceDeleted(Series $series)
    {
        Cache::forget('series.' . $series->category->id);
        Cache::forget('upcoming');
    }
}
