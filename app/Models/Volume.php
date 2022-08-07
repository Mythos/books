<?php

namespace App\Models;

use App\Constants\VolumeStatus;
use App\Helpers\IsbnHelpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;
use Image;
use Storage;

/**
 * App\Models\Volume
 *
 * @property int $id
 * @property int $number
 * @property string|null $publish_date
 * @property string|null $isbn
 * @property int $status
 * @property int $series_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $price
 * @property int $ignore_in_upcoming
 * @property string|null $image_url
 * @property-read string $image
 * @property-read bool $image_exists
 * @property-read string $image_path
 * @property-read bool $image_thumbnail
 * @property-read string $isbn_formatted
 * @property-read string $name
 * @property-read string $publish_date_formatted
 * @property-read string $status_class
 * @property-read string $status_name
 * @property-read \App\Models\Series $series
 * @method static \Illuminate\Database\Eloquent\Builder|Volume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume query()
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereIgnoreInUpcoming($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Volume whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Volume extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'publish_date',
        'isbn',
        'status',
        'price',
        'ignore_in_upcoming',
        'series_id',
        'image_url',
    ];

    /**
     * Get the volume's formatted publish date.
     *
     * @return string
     */
    public function getPublishDateFormattedAttribute() : ?string
    {
        if (empty($this->publish_date)) {
            return null;
        }

        return Carbon::parse($this->publish_date)->format(auth()->user()->date_format);
    }

    /**
     * Get the volume's name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        if ($this->series->total == 1) {
            return $this->series->name;
        }

        return "{$this->series->name} {$this->number}";
    }

    /**
     * Get the volume's ISBN.
     *
     * @return string
     */
    public function getIsbnFormattedAttribute(): ?string
    {
        if (auth()->user()->format_isbns_enabled) {
            return IsbnHelpers::format($this->isbn);
        }

        return $this->isbn;
    }

    /**
     * Get the volume's status name.
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        switch ($this->status) {
            case VolumeStatus::NEW:
                return __('New');
            case VolumeStatus::ORDERED:
                return __('Ordered');
            case VolumeStatus::SHIPPED:
                return __('Shipped');
            case VolumeStatus::DELIVERED:
                return __('Delivered');
            case VolumeStatus::READ:
                return __('Read');
            default:
                return __('Unknown');
        }
    }

    /**
     * Get the volume's status CSS class.
     *
     * @return string
     */
    public function getStatusClassAttribute(): string
    {
        switch ($this->status) {
            case VolumeStatus::NEW:
                return 'table-danger';
            case VolumeStatus::ORDERED:
                return 'table-warning';
            case VolumeStatus::SHIPPED:
                return 'table-info';
            case VolumeStatus::DELIVERED:
                return 'table-primary';
            case VolumeStatus::READ:
                return 'table-success';
            default:
                return '';
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
    public function getRouteKeyName(): string
    {
        return 'number';
    }

    /**
     * Get the volumes' image path.
     *
     * @return string
     */
    public function getImagePathAttribute(): string
    {
        return 'series/' . $this->series_id . '/volumes/' . $this->id;
    }

    /**
     * Get the volumes' image.
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        $path = 'storage/' . $this->image_path . '/';
        $file = $path . 'cover.' . config('images.type');
        if ($this->series->is_nsfw && !session('show_nsfw', false)) {
            $file = $path . 'cover_sfw.' . config('images.type');
        }

        if (File::exists($file)) {
            return url($file);
        } else {
            return url('images/placeholder.png');
        }
    }

    /**
     * Get the volumes' image status.
     *
     * @return bool
     */
    public function getImageExistsAttribute(): bool
    {
        return Storage::disk('public')->exists($this->image_path);
    }

    /**
     * Get the volumes' image thumbnail.
     *
     * @return bool
     */
    public function getImageThumbnailAttribute(): ?string
    {
        $path = 'storage/thumbnails/' . $this->image_path . '/';
        $file = $path . 'cover.' . config('images.type');
        if ($this->series->is_nsfw && !session('show_nsfw', false)) {
            $file = $path . 'cover_sfw.' . config('images.type');
        }
        if (File::exists($file)) {
            return url($file);
        } else {
            return url('images/placeholder.png');
        }
    }
}
