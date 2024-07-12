<?php

namespace App\Models;

use App\Models\Carts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = [
        'id_user',
        'name',
        'email',
        'password',
        'cpf',
        'address',
        'born_date',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id_user = (string) Str::uuid();
        });

        static::created(function ($user) {
            $user->cart()->create(['id_cart' => $user->id_user]);
        });
    }

    public function cart()
    {
        return $this->hasOne(Carts::class, 'id_cart', 'id_user');
    }

    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    public function getAuthIdentifier()
    {
        return $this->id_user;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}

