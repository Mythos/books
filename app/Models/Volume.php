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
 * @property string $isbn
 * @property int $status
 * @property int $series_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Series $series
 * @method static \Illuminate\Database\Eloquent\Builder|Volume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume query()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $status_class
 * @property-read string $status_name
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
        'series_id',
    ];

    /**
     * Set the volume's ISBN.
     *
     * @param  string  $value
     * @return void
     */
    public function getIsbnFormattedAttribute()
    {
        return IsbnHelpers::format($this->isbn);
    }

    /**
     * Get the volume's status name.
     *
     * @return string
     */
    public function getStatusNameAttribute() : string
    {
        switch ($this->status) {
            case 0:
                return __('New');
            case 1:
                return __('Ordered');
            case 2:
                return __('Delivered');
        }
    }

    /**
     * Get the volume's status CSS class.
     *
     * @return string
     */
    public function getStatusClassAttribute() : string
    {
        switch ($this->status) {
            case 0:
                return 'table-danger';
            case 1:
                return 'table-warning';
            case 2:
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
    public function getRouteKeyName() : string
    {
        return 'number';
    }
}
