<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkladchinaImage extends Model
{
    protected $fillable = [
        'path',
        'position',
        'image_links',
    ];

    protected $casts = [
        'image_links' => 'array',
    ];

    public function skladchina(): BelongsTo
    {
        return $this->belongsTo(Skladchina::class);
    }
}
