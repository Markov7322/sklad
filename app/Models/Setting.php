<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function value(string $key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }
}
