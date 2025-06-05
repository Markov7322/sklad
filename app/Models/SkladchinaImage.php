<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkladchinaImage extends Model
{
    protected $fillable = [
        'path',
        'position',
    ];

    public function skladchina(): BelongsTo
    {
        return $this->belongsTo(Skladchina::class);
    }
}
