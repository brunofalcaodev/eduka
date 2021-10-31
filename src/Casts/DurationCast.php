<?php

namespace Eduka\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DurationCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        if ($value !== null) {
            return substr('00'.floor($value / 60), -2).':'.substr('00'.$value % 60, -2);
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if ($value !== null) {
            return explode(':', $value)[0] * 60 + explode(':', $value)[1];
        }
    }
}
