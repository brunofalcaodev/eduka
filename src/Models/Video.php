<?php

namespace Eduka\Models;

use Eduka\Casts\DurationCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Video extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $guarded = [];

    protected $appends = ['is_completed'];

    protected $casts = [
        'duration' => DurationCast::class,
        'is_visible' => 'boolean',
        'is_active' => 'boolean',
        'is_free' => 'boolean',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        /* Image conversion for social media (facebook, twitter) */
        $this->addMediaConversion('social')
             ->fit(Manipulations::FIT_CONTAIN, 1200, 600);

        /* Image conversion for the Nova backoffice thumbs */
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CONTAIN, 450, 337);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'videos_completed')
                    ->withTimestamps();
    }

    public function getIsCompletedAttribute()
    {
        return Auth::guest() ?
                        false :
                        $this->completedBy()
                             ->where('videos_completed.user_id', Auth::id())->count() > 0;
    }

    /**
     * Dirty way to get the next video. Just return the next id if it
     * exists. If not, return null.
     *
     * @return Eduka\Models\Video
     */
    public function getNextAttribute()
    {
        return $this->active()->next()
                    ->firstOr(function () {
                    });
    }

    public function scopeNext($query)
    {
        return $query->where('id', $this->id + 1);
    }

    /**
     * Dirty way to get the previous video. Just return the previous id if it
     * exists. If not, return null.
     *
     * @return Eduka\Models\Video
     */
    public function getPreviousAttribute()
    {
        return $this->active()->previous()
                    ->firstOr(function () {
                    });
    }

    public function scopePrevious($query)
    {
        return $query->where('id', $this->id - 1);
    }

    public function scopeFree()
    {
        return $this->where('is_free', true);
    }

    public function scopeActive()
    {
        return $this->where('is_active', true);
    }

    public function scopePremium($query)
    {
        return $this->where('is_free', false);
    }

    public function getIsRecordedAttribute($value)
    {
        return $this->duration != null && $this->vimeo_id != null;
    }

    public static function createWithImage(array $data, string $path)
    {
        $video = static::create($data);

        $video->save();

        $video->addMedia(__DIR__."/../../resources/images/videos/{$path}")
              ->preservingOriginal()
              ->toMediaCollection();

        return $video;
    }
}
