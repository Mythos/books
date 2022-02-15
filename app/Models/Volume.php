<?php

namespace App\Models;

use App\Helpers\IsbnHelpers;
use Carbon\Carbon;
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
 * @property-read string $publish_date_formatted
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
     * @var array<int, string>
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
     * Get the volume's formatted publish date.
     *
     * @return string
     */
    public function getPublishDateFormattedAttribute() : ?string
    {
        if (empty($this->publish_date)) {
            return null;
        }

        return Carbon::parse($this->publish_date)->format(auth()->user()->date_format);
    }

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
    public function getIsbnFormattedAttribute(): ?string
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
        $status = '';
        switch ($this->status) {
            case 0:
                $status = __('New');
            case 1:
                $status = __('Ordered');
            case 2:
                $status = __('Shipped');
            case 3:
                $status = __('Delivered');
            case 4:
                $status = __('Read');
            default:
                $status = __('Unknown');
        }

        return  $status;
    }

    /**
     * Get the volume's status CSS class.
     *
     * @return string
     */
    public function getStatusClassAttribute(): string
    {
        $class = '';
        switch ($this->status) {
            case 0:
                $class = 'table-danger';
            case 1:
                $class = 'table-warning';
            case 2:
                $class = 'table-info';
            case 3:
                $class = 'table-primary';
            case 4:
                $class = 'table-success';
            default:
                $class = '';
        }

        return $class;
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
