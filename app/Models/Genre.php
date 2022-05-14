<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Genre
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $type_class
 * @property-read string $type_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Series[] $series
 * @property-read int|null $series_count
 * @method static \Illuminate\Database\Eloquent\Builder|Genre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre query()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Genre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Get the genres' type name.
     *
     * @return string
     */
    public function getTypeNameAttribute(): string
    {
        switch ($this->type) {
            case 0:
                return __('Demographics');
            case 1:
                return __('Genre');
            default:
                return '';
        }
    }

    /**
     * Get the genres' type CSS class.
     *
     * @return string
     */
    public function getTypeClassAttribute(): string
    {
        switch ($this->type) {
            case 0:
                return 'badge bg-primary';
            case 1:
                return 'badge bg-secondary';
            default:
                return '';
        }
    }

    /**
     * Get the series associated with the Genre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Series::class);
    }
}
