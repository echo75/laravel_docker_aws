<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'firstName',
        'surName',
        'email',
        'salt',
        'hash',
    ];

    protected $hidden = [
        'salt',
        'hash',
    ];

    public function movies()
    {
        return $this->hasMany(UserMovie::class, 'user_id');
    }
}
