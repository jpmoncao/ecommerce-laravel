<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariations extends Model
{
    use HasFactory;

    public $fillable = [
        'id_product_variation',
        'variant',
        'amount',
        'product_id',
    ];

    public function product_owner()
    {
        return $this->belongsTo(Product::class);
    }
}
