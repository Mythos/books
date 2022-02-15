<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $price
 * @property string|null $release_date
 * @property int $status
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read string $image
 * @property-read string $status_class
 * @property-read string $status_name
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $image_path
 */
class Article extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'release_date',
        'status',
        'category_id',
    ];

    /**
     * Get the category that owns the Article
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
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the article' image.
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        $path = 'storage/articles/' . $this->id . '/';

        return url($path . 'image.jpg');
    }

    /**
     * Get the article's status name.
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
            default:
                return __('Unknown');
        }
    }

    /**
     * Get the article's status CSS class.
     *
     * @return string
     */
    public function getStatusClassAttribute(): string
    {
        switch ($this->status) {
            case 0:
                return 'badge bg-danger';
            case 1:
                return 'badge bg-warning';
            case 2:
                return 'badge bg-info';
            case 3:
                return 'badge bg-success';
            default:
                return  '';
        }
    }

    /**
     * Get the article's image path.
     *
     * @return string
     */
    public function getImagePathAttribute(): string
    {
        return 'articles/' . $this->id;
    }
}
