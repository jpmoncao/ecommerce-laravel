<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payments extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'id_payment';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_financial',
        'amount',
        'discount',
        'surcharge',
        'method',
        'authorized',
        'sequencial',
        'created_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $payment->id_payment = (string) Str::uuid();
        });
    }

}