<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;  // Adicione esta importação para gerar UUIDs

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
            $productVariation->id_product_variation = (string) Str::uuid();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
