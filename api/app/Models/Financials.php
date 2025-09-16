<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financials extends Model
{
    use HasFactory;
    protected $table = 'financials';
    protected $primaryKey = 'id_financial';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'order_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function totalAmount()
    {
        $orderTotalAmount = Orders::where('id_order', $this->order_id)->value('total_amount');
        return $orderTotalAmount;
    }

    public function paidAmount()
    {
        $financialPaidAmount = Payments::where('id_financial', $this->id_financial)
            ->where('authorized', true)
            ->sum('amount');
        return $financialPaidAmount;
    }

    public function lastPaymentSequencial()
    {
        $latestPayment = Payments::where('id_financial', $this->id_financial)
            ->where('authorized', true)
            ->latest();

        if (!$latestPayment)
            return 0;

        return $latestPayment->sequencial;
    }

}
