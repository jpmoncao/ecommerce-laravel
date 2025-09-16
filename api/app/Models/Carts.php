<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Carts extends Model
{
    use HasFactory;
    public $fillable = [
        'id_cart'
    ];

    public function items()
    {
        return $this->hasMany(CartItems::class, 'cart_id', 'id_cart');
    }
}
