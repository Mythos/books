<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GenreSeries
 *
 * @property int $id
 * @property int $genre_id
 * @property int $series_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Genre $genre
 * @property-read \App\Models\Series $series
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries query()
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenreSeries whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GenreSeries extends Model
{
    /**
     * Get the series associated with the genre.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the genre associated with the series.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
}
