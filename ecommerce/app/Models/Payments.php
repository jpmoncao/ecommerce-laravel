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

    public function toAuthorize()
    {
        $financial = Financials::where('id_financial', $this->financial_id);

        // Obtém os valores do financeiro
        $financialPaidAmount = $financial->paidAmount();
        $financialTotalAmount = $financial->totalAmount();

        // Se o valor pago for igual o valor total (último pagamento)
        if ($financialPaidAmount == $financialTotalAmount) {
            // obtém pedido pelo id
            $order = Orders::where('id_order', $financial->order_id);

            // Caso não encontre, dispara mensagem de não encontrado
            if (!$order)
                return response()->json(['message' => 'Order not found!'], 404);

            // Atualiza financeiro para status "Pago"
            $financial->update(['status' => 'paid']);

            //Atualiza pedido para status "Completo"
            $order->update(['status' => 'completed']);
        }
    }

}