<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_order',
        'user_id',
        'status',
        'total_amount',
        'created_at',
        'updated_at',
    ];

    public function items()
    {
        return $this->hasMany(CartItems::class, 'order_id', 'id_order');
    }
}
