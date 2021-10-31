<?php

namespace Eduka\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
