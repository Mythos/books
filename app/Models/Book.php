<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nicebooks\Isbn\Isbn;
use Nicebooks\Isbn\IsbnTools;

/**
 * App\Models\Book
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
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $status_class
 * @property-read string $status_name
 */
class Book extends Model
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
     * Set the book's ISBN.
     *
     * @param  string  $value
     * @return void
     */
    public function setIsbnAttribute($value)
    {
        $isbn = Isbn::of($value);
        $this->attributes['isbn'] = $isbn->to13();
    }

    /**
     * Set the book's ISBN.
     *
     * @param  string  $value
     * @return void
     */
    public function getIsbnAttribute($value)
    {
        $tools = new IsbnTools();
        return $tools->format($value);
        // $this->attributes['isbn'] = strtolower($value);
    }

    /**
     * Get the book's status name.
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
     * Get the book's status CSS class.
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
     * Get the series that owns the Book
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
