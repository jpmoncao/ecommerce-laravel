<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'id_order';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cart_id',
        'status',
        'total_amount',
        'created_at',
        'updated_at',
    ];

    public function items()
    {
        return $this->hasMany(CartItems::class, 'order_id', 'id_order');
    }

    public function toComplete()
    {
        $this->update(['status' => 'completed']);

        $items = $this->items();
        foreach ($items as $item) {
            $itemIdProductVariation = $item->id_product_variation;
            $itemQuantitity = $item->quantitity;

            
        }
    }
}
