<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Series
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $status
 * @property int|null $total
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @property-read int|null $books_count
 * @property-read \App\Models\Category $category
 * @property-read string $image
 * @property-read string $status_class
 * @property-read string $status_name
 * @method static \Illuminate\Database\Eloquent\Builder|Series newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series query()
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Series extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'total',
        'category_id',
    ];

    /**
     * Get the series' status name.
     *
     * @return string
     */
    public function getStatusNameAttribute() : string
    {
        switch ($this->status) {
            case 0:
                return __('New');
            case 1:
                return __('Ongoing');
            case 2:
                return __('Finished');
        }
    }

    /**
     * Get the series' status CSS class.
     *
     * @return string
     */
    public function getStatusClassAttribute() : string
    {
        switch ($this->status) {
            case 0:
                return __('badge badge-secondary');
            case 1:
                return __('badge badge-warning');
            case 2:
                return __('badge badge-success');
        }
    }

    /**
     * Get the series' count of new books.
     *
     * @return string
     */
    public function getNewBooksCountAttribute() : string
    {
        return $this->books()->whereStatus('0')->count();
    }

    /**
     * Get the series' count of ordered books.
     *
     * @return string
     */
    public function getOrderedBooksCountAttribute() : string
    {
        return $this->books()->whereStatus('1')->count();
    }

    /**
     * Get the series' count of delivered books.
     *
     * @return string
     */
    public function getDeliveredBooksCountAttribute() : string
    {
        return $this->books()->whereStatus('2')->count();
    }

    /**
     * Get the series' image.
     *
     * @return string
     */
    public function getImageAttribute() : string
    {
        return url('storage/series/' . $this->slug . '.jpg');
    }

    /**
     * Get all of the books for the Series
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get the category that owns the Series
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return 'slug';
    }
}
