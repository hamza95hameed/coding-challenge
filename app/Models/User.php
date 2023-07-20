<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Connection;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function connections()
    {
        return $this->hasMany(Connection::class, 'user_id');
    }

    public function receivedConnections()
    {
        return $this->hasMany(Connection::class, 'connected_user_id');
    }

    public function commonConnections(User $user)
    {
        return $this->connections()->whereHas('user', function ($query) use ($user) {
            $query->whereIn('user_id', $user->connections()->pluck('connected_user_id'));
        });
    }
}
