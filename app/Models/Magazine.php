<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Magazine
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Series> $series
 * @property-read int|null $series_count
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine query()
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Magazine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Magazine extends Model
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
    ];

    /**
     * Get all of the series for the Magazine
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Series::class);
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
