<?php

namespace Eduka\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'course';

    protected $casts = [
        'is_active' => 'boolean',
        'meta_tags' => 'array',
        'launched_at' => 'datetime',
    ];

    protected $appends = [
        'url',
        'from',
    ];

    public function getUrlAttribute()
    {
        return env('APP_URL');
    }

    public function getFromAttribute()
    {
        return [
            'name' => env('EDUKA_ADMIN_NAME'),
            'email' => env('EDUKA_ADMIN_EMAIL'), ];
    }
}
