<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MagazineSeries
 *
 * @property int $id
 * @property int $magazine_id
 * @property int $series_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Magazine $magazine
 * @property-read \App\Models\Series $series
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries query()
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries whereMagazineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MagazineSeries whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MagazineSeries extends Model
{
    use HasFactory;

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
     * Get the magazine associated with the series.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function magazine(): BelongsTo
    {
        return $this->belongsTo(Magazine::class);
    }
}
