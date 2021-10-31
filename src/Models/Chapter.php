<?php

namespace Eduka\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Chapter extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $guarded = [];

    protected $appends = ['total_minutes', 'total_videos', 'videos_completed'];

    public function registerMediaConversions(Media $media = null): void
    {
        /* Image conversion for the website hero images */
        $this->addMediaConversion('featured')
             ->fit(Manipulations::FIT_CONTAIN, 570, 513);

        /* Image conversion for the website hero images */
        $this->addMediaConversion('featured@2x')
             ->fit(Manipulations::FIT_CONTAIN, 1140, 1026);

        /* Image conversion for the Nova backoffice thumbs */
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CONTAIN, 450, 250);

        /* Image conversion for social media (facebook, twitter) */
        $this->addMediaConversion('social')
             ->performOnCollections('social')
             ->fit(Manipulations::FIT_CONTAIN, 1200, 600);
    }

    public function registerMediaCollections(): void
    {
        // Website social image for facebook and twitter.
        $this->addMediaCollection('social')
             ->singleFile();
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function visibleVideos()
    {
        return $this->hasMany(Video::class)
                    ->where('is_visible', true);
    }

    public function activeVideos()
    {
        return $this->hasMany(Video::class)
                    ->where('is_active', true);
    }

    public function premiumVideos()
    {
        return $this->hasMany(Video::class)
                    ->where('is_free', false);
    }

    /**
     * Return the total minutes of all the videos from this chapter.
     *
     * @return int
     */
    public function getTotalMinutesAttribute()
    {
        $total = 0;

        $this->videos->each(function ($video) use (&$total) {
            $total += $video->attributes['duration'];
        });

        return ceil($total / 60);
    }

    public function getTotalVideosAttribute()
    {
        return $this->videos->count();
    }

    public function getVideosCompletedAttribute()
    {
        if (Auth::guest()) {
            return collect();
        }

        return $this->videos()->join('videos_completed', function ($join) {
            $join->on('videos.id', '=', 'videos_completed.video_id')
                 ->where('videos_completed.user_id', '=', Auth::id());
        })->get();
    }
}
