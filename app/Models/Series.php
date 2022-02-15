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
 * @property int|null $is_nsfw
 * @property string|null $default_price
 * @property int|null $publisher_id
 * @property int $subscription_active
 * @property int|null $mangapassion_id
 * @property-read \App\Models\Category $category
 * @property-read string $completion_status
 * @property-read string $completion_status_class
 * @property-read string $completion_status_name
 * @property-read string $image
 * @property-read string $read_volumes_count
 * @property-read string $status_class
 * @property-read string $status_name
 * @property-read string $total_worth
 * @property-read string $unread_volumes_count
 * @property-read \App\Models\Publisher|null $publisher
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Volume[] $volumes
 * @property-read int|null $volumes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Series newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series query()
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereDefaultPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereIsNsfw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereMangapassionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series wherePublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSubscriptionActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $image_path
 */
class Series extends Model
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
        'status',
        'total',
        'is_nsfw',
        'default_price',
        'category_id',
        'publisher_id',
        'subscription_active',
        'mangapassion_id',
    ];

    /**
     * Get the series' unread volumes count.
     *
     * @return string
     */
    public function getUnreadVolumesCountAttribute(): ?int
    {
        return $this->volumes->where('status', '=', '3')->count();
    }

    /**
     * Get the series' read volumes count.
     *
     * @return string
     */
    public function getReadVolumesCountAttribute(): ?int
    {
        return $this->volumes->where('status', '=', '4')->count();
    }

    /**
     * Get the series' status name.
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        switch ($this->status) {
            case 0:
                return __('New');
            case 1:
                return __('Ongoing');
            case 2:
                return __('Finished');
            default:
                return __('Unknown');
        }
    }

    /**
     * Get the series' status CSS class.
     *
     * @return string
     */
    public function getStatusClassAttribute(): string
    {
        switch ($this->status) {
            case 0:
                return 'badge bg-secondary';
            case 1:
                return 'badge bg-warning';
            case 2:
                return 'badge bg-success';
            default:
                return '';
        }
    }

    /**
     * Get the series' completion status CSS class.
     *
     * @return string
     */
    public function getCompletionStatusClassAttribute(): string
    {
        switch ($this->completion_status) {
            case false:
                return 'badge bg-danger';
            case true:
                return 'badge bg-success';
        }
    }

    /**
     * Get the series' completion status display name.
     *
     * @return string
     */
    public function getCompletionStatusNameAttribute(): string
    {
        switch ($this->completion_status) {
            case false:
                return __('Incomplete');
            case true:
                return __('Complete');
        }
    }

    /**
     * Get the series' completion status.
     *
     * @return string
     */
    public function getCompletionStatusAttribute(): bool
    {
        if (empty($this->total)) {
            return false;
        }

        return $this->total == $this->volumes->whereIn('status', ['3', '4'])->count();
    }

    /**
     * Get the series' image.
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        $path = 'storage/' . $this->image_path . '/';
        if ($this->is_nsfw && !session('show_nsfw', false)) {
            return url($path . 'cover_sfw.jpg');
        }

        return url($path . 'cover.jpg');
    }

    /**
     * Get the series' total worth which is owned.
     *
     * @return string
     */
    public function getTotalWorthAttribute(): string
    {
        return $this->volumes->whereIn('status', ['3', '4'])->sum('price');
    }

    /**
     * Get all of the volumes for the Series
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function volumes(): HasMany
    {
        return $this->hasMany(Volume::class);
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
     * Get the publisher that owns the Series
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
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
     * Get the series' image path.
     *
     * @return string
     */
    public function getImagePathAttribute(): string
    {
        return 'series/' . $this->id;
    }
}
