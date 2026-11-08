<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'recovery_hash', 'role', 'bio'];

    protected $hidden = ['password', 'remember_token', 'recovery_hash', 'pic'];

    protected $casts = ['password' => 'hashed', 'last_seen_at' => 'datetime'];
}
