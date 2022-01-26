<?php

namespace App\Models;

use App\Helpers\IsbnHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Volume
 *
 * @property int $id
 * @property int $number
 * @property string|null $publish_date
 * @property string|null $isbn
 * @property int $status
 * @property int $series_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $price
 * @property int $ignore_in_upcoming
 * @property-read string $isbn_formatted
 * @property-read string $name
 * @property-read string $status_class
 * @property-read string $status_name
 * @property-read \App\Models\Series $series
 * @method static \Illuminate\Database\Eloquent\Builder|Volume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume query()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereIgnoreInUpcoming($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Volume extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'publish_date',
        'isbn',
        'status',
        'price',
        'ignore_in_upcoming',
        'series_id',
    ];

    /**
     * Get the volume's name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        if ($this->series->total == 1) {
            return $this->series->name;
        }

        return "{$this->series->name} {$this->number}";
    }

    /**
     * Get the volume's ISBN.
     *
     * @return string
     */
    public function getIsbnFormattedAttribute(): string
    {
        if (auth()->user()->format_isbns_enabled) {
            return IsbnHelpers::format($this->isbn);
        }

        return $this->isbn;
    }

    /**
     * Get the volume's status name.
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        switch ($this->status) {
            case 0:
                return __('New');
            case 1:
                return __('Ordered');
            case 2:
                return __('Shipped');
            case 3:
                return __('Delivered');
            case 4:
                return __('Read');
        }
    }

    /**
     * Get the volume's status CSS class.
     *
     * @return string
     */
    public function getStatusClassAttribute(): string
    {
        switch ($this->status) {
            case 0:
                return 'table-danger';
            case 1:
                return 'table-warning';
            case 2:
                return 'table-info';
            case 3:
                return 'table-primary';
            case 4:
                return 'table-success';
        }
    }

    /**
     * Get the series that owns the Volume
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'number';
    }
}
