<?php

namespace App\Models;

use App\Models\Carts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method \Laravel\Sanctum\NewAccessToken createToken(string $name, array $abilities = ['*'], \DateTimeInterface|null $expiresAt = null)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = [
        'id_user',
        'name',
        'email',
        'password',
        'cpf_cnpj',
        'address',
        'born_date',
        'email_verified_at',
        'is_guest'
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
