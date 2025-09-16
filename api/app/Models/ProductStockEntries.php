<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductStockEntries extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_product_stock_entry';
    public $incrementing = false;
    public $keyType = 'string';

    public $fillable = [
        'product_variation_id',
        'quantity',
        'observation',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product_variation_stock_entry) {
            $product_variation_stock_entry->id_product_stock_entry = (string) Str::uuid();
        });
    }
}
