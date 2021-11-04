<?php

namespace Eduka\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Affiliate extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'commission' => 'integer',
        'paddle_vendor_id' => 'integer',
    ];
}
