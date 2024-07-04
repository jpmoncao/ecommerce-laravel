<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariations;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_product';
    public $incrementing = true;
    public $keyType = 'integer';

    protected $fillable = [
        'id_product',
        'name',
        'description'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariations::class, 'product_id', 'id_product');
    }
}
