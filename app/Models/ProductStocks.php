<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductStocks extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_stock';
    public $incrementing = false;
    public $keyType = 'string';

    public $fillable = [
        'id_stock',
        'quantity',
    ];
}
