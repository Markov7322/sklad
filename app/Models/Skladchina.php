<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skladchina extends Model
{
    public const STATUS_DONATION = 'donation';
    public const STATUS_ISSUE = 'issue';
    public const STATUS_AVAILABLE = 'available';

    public static function statuses(): array
    {
        return [
            self::STATUS_DONATION => 'Сбор донатов',
            self::STATUS_ISSUE => 'Выдача',
            self::STATUS_AVAILABLE => 'Доступно',
        ];
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover',
        'image_path',
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
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('paid', 'access_until');
    }

    public function images(): HasMany
    {
        return $this->hasMany(SkladchinaImage::class)->orderBy('position');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DONATION => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            self::STATUS_ISSUE => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            self::STATUS_AVAILABLE => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    /**
     * Accessor for the SEO title.
     *
     * Allows templates to use `$skladchina->title` while keeping backward
     * compatibility with the existing `name` attribute.
     */
    public function getTitleAttribute(): string
    {
        return $this->name;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
