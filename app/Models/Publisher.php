<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Publisher
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $image_url
 * @property-read string $image
 * @property-read string $image_path
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Series> $series
 * @property-read int|null $series_count
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Publisher whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Publisher extends Model
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
        'image_url',
    ];

    /**
     * Get all of the series for the Publisher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
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
     * Get the publisher's image path.
     *
     * @return string
     */
    public function getImagePathAttribute(): string
    {
        return 'publishers/' . $this->id;
    }

    /**
     * Get the publisher's image.
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        $path = 'storage/' . $this->image_path . '/';
        $file = $path . 'cover.' . config('images.type');
        if ($this->is_nsfw && !session('show_nsfw', false)) {
            $file = $path . 'cover_sfw.' . config('images.type');
        }

        if (File::exists($file)) {
            return url($file);
        } else {
            return url('images/placeholder.png');
        }
    }
}
