<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skladchina extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cover',
        'full_price',
        'member_price',
        'status',
        'attachment',
        'organizer_id',
        'category_id',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
