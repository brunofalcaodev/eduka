<?php

namespace Eduka\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'course';

    protected $casts = [
        'is_active' => 'boolean',
        'meta_tags' => 'array',
        'launched_at' => 'datetime',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        /* Image conversion for the website hero images */
        $this->addMediaConversion('featured')
             ->fit(Manipulations::FIT_CONTAIN, 769, 577);

        /* Image conversion for the website hero images */
        $this->addMediaConversion('featured@2x')
             ->fit(Manipulations::FIT_CONTAIN, 1538, 1154);

        /* Image conversion for social media (facebook, twitter) */
        $this->addMediaConversion('social')
             ->performOnCollections('social')
             ->fit(Manipulations::FIT_CONTAIN, 1200, 600);

        /* Image conversion for the Nova backoffice thumbs */
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CROP, 450, 337);
    }

    public function registerMediaCollections(): void
    {
        // Course social image for facebook and twitter.
        $this->addMediaCollection('social')
             ->singleFile();
    }
}
