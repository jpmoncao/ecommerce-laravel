<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CartItems extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $primaryKey = 'id_cart_item';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_cart_item',
        'cart_id',
        'order_id',
        'product_variation_id',
        'quantity',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cart_item) {
            $cart_item->id_cart_item = (string) Str::uuid();
        });
    }

    public function cart()
    {
        return $this->belongsTo(Carts::class, 'cart_id', 'id_cart');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id_order');
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariations::class, 'product_variation_id', 'id_product_variation');
    }
}
