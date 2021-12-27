<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Volume[] $volumes
 * @property-read int|null $volumes_count
 * @property-read \App\Models\Category $category
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
 * @property-read string $completion_status
 * @property-read string $completion_status_class
 * @property-read string $completion_status_name
 * @property-read string $delivered_volumes_count
 * @property-read string $new_volumes_count
 * @property-read string $ordered_volumes_count
 * @property int|null $is_nsfw
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereIsNsfw($value)
 * @property-read string $image
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
        'is_nsfw',
        'category_id',
    ];

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
        return $this->total == $this->deliveredVolumesCount;
    }

    /**
     * Get the series' count of new volumes.
     *
     * @return string
     */
    public function getNewVolumesCountAttribute(): string
    {
        return $this->volumes->where('status', '0')->count();
    }

    /**
     * Get the series' count of ordered volumes.
     *
     * @return string
     */
    public function getOrderedVolumesCountAttribute(): string
    {
        return $this->volumes->where('status', '1')->count();
    }

    /**
     * Get the series' count of delivered volumes.
     *
     * @return string
     */
    public function getDeliveredVolumesCountAttribute(): string
    {
        return $this->volumes->where('status', '3')->count();
    }

    /**
     * Get the series' image.
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        $path = 'storage/series/' . $this->id . '/';
        if ($this->is_nsfw && !session('show_nsfw', false)) {
            return url($path . 'cover_sfw.jpg');
        }
        return url($path . 'cover.jpg');
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
}
