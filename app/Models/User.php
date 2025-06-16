<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Skladchina;
use App\Models\Transaction;
use App\Models\Topup;

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
        'notify_status_changes',
        'notify_site',
        'notify_balance_changes',
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
            'notify_status_changes' => 'boolean',
            'notify_site' => 'boolean',
            'notify_balance_changes' => 'boolean',
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

    public function topups()
    {
        return $this->hasMany(Topup::class);
    }

    public function isSubscribed(Skladchina $skladchina): bool
    {
        return $this->skladchinas()->where('skladchina_id', $skladchina->id)->exists();
    }
}
