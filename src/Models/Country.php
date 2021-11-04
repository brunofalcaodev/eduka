<?php

namespace Eduka\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'ppp_index' => 'float',
    ];
}
