<?php

namespace App\Models;

use App\Constants\SeriesStatus;
use App\Constants\VolumeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property string|null $image_url
 * @property string|null $description
 * @property int|null $source_status
 * @property string|null $source_name
 * @property string|null $source_name_romaji
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read int|null $genres_count
 * @property-read int $completion_status
 * @property-read string $completion_status_class
 * @property-read string $completion_status_name
 * @property-read mixed $demographics
 * @property-read mixed $genre_tags
 * @property-read string $image
 * @property-read string $image_path
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
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereIsNsfw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereMangapassionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series wherePublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSourceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSourceNameRomaji($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSourceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSubscriptionActive($value)
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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'total',
        'is_nsfw',
        'default_price',
        'category_id',
        'publisher_id',
        'subscription_active',
        'image_url',
        'mangapassion_id',
        'source_status',
        'source_name',
        'source_name_romaji',
    ];

    /**
     * Get the series' unread volumes count.
     *
     * @return string
     */
    public function getUnreadVolumesCountAttribute(): ?int
    {
        return $this->volumes->where('status', '=', VolumeStatus::DELIVERED)->count();
    }

    /**
     * Get the series' read volumes count.
     *
     * @return string
     */
    public function getReadVolumesCountAttribute(): ?int
    {
        return $this->volumes->where('status', '=', VolumeStatus::READ)->count();
    }

    /**
     * Get the series' status name.
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        switch ($this->status) {
            case SeriesStatus::NEW:
                return __('Announced');
            case SeriesStatus::ONGOING:
                return __('Ongoing');
            case SeriesStatus::FINISHED:
                return __('Finished');
            case SeriesStatus::PAUSED:
                return __('Paused');
            case SeriesStatus::CANCELED:
                return __('Canceled');
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
            case SeriesStatus::NEW:
                return 'badge bg-secondary';
            case SeriesStatus::ONGOING:
                return 'badge bg-primary';
            case SeriesStatus::FINISHED:
                return 'badge bg-success';
            case SeriesStatus::PAUSED:
                return 'badge bg-warning';
            case SeriesStatus::CANCELED:
                return 'badge bg-danger';
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
            case 0:
                return 'badge bg-danger';
            case 1:
                return 'badge bg-primary';
            case 2:
                return 'badge bg-success';
            default:
                return '';
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
            case 1:
            case 2:
                return __('Complete');
            default:
                return __('Incomplete');
        }
    }

    /**
     * Get the series' completion status.
     *
     * @return int
     */
    public function getCompletionStatusAttribute(): int
    {
        if (empty($this->total)) {
            return false;
        }

        $volumes = $this->volumes->whereNotNull('publish_date')
        ->filter(function ($volume) {
            return $volume->status == VolumeStatus::SHIPPED || $volume->status == VolumeStatus::DELIVERED || $volume->status == VolumeStatus::READ
                || $volume->publish_date <= now()
                || (!$this->subscription_active && $volume->status == VolumeStatus::ORDERED);
        });
        $total = $volumes->count();
        $possessed = $volumes->whereIn('status', [VolumeStatus::DELIVERED, VolumeStatus::READ])->count();
        $read = $volumes->where('status', VolumeStatus::READ)->count();

        if ($total == 0) {
            return 0;
        }
        if ($total - $read == 0) {
            return 2;
        }

        return $total - $possessed == 0;
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
            return url($path . 'cover_sfw.' . config('images.type'));
        }

        return url($path . 'cover.' . config('images.type'));
    }

    /**
     * Get the series' total worth which is owned.
     *
     * @return string
     */
    public function getTotalWorthAttribute(): string
    {
        return $this->volumes->whereIn('status', [VolumeStatus::DELIVERED, VolumeStatus::READ])->sum('price');
    }

    /**
     * Get the series' demographics.
     */
    public function getDemographicsAttribute()
    {
        return $this->genres->where('type', '0')->sortBy('name')->first();
    }

    /**
     * Get the series' genres.
     */
    public function getGenreTagsAttribute()
    {
        return $this->genres->where('type', '1')->sortBy('name');
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
     * Get the genres associated with the Series
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
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
