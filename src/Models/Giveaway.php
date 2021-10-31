<?php

namespace Eduka\Models;

use Illuminate\Database\Eloquent\Model;

class Giveaway extends Model
{
    protected $guarded = [];

    protected $table = 'giveaway';

    protected $casts = [
        'won' => 'boolean',
        'contest_number' => 'integer',
    ];
}
