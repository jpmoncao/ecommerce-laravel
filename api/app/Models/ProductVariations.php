<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariations extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_product_variation';
    public $incrementing = false;
    public $keyType = 'string';

    public $fillable = [
        'id_product_variation',
        'variation',
        'amount',
        'product_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productVariation) {
            if (empty($productVariation->id_product_variation)) {
                $productVariation->id_product_variation = (string) Str::uuid();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id_product');
    }

    public function stock()
    {
        return $this->hasOne(ProductStocks::class, 'id_stock', 'id_product_variation');
    }

    public function stockEntries()
    {
        return $this->hasMany(ProductStockEntries::class, 'product_variation_id', 'id_product_variation');
    }
}
