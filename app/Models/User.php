<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Skladchina;
use App\Models\Transaction;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'banned',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'banned' => 'boolean',
            'balance' => 'decimal:2',
        ];
    }

    public function skladchinas()
    {
        return $this->belongsToMany(Skladchina::class)
            ->withTimestamps()
            ->withPivot('paid', 'access_until');
    }

    public function organizedSkladchinas()
    {
        return $this->hasMany(Skladchina::class, 'organizer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isSubscribed(Skladchina $skladchina): bool
    {
        return $this->skladchinas()->where('skladchina_id', $skladchina->id)->exists();
    }
}
