<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\Financials;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Utils\ValidatorRequest;

class PaymentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cria objeto de validação
        $validate = new ValidatorRequest($request, [
            'financial_id' => 'required|integer',
            'amount' => 'required|float|min:0.01',
            'method' => 'required|string',
            'discount' => 'required|float|min:0.01',
            'surcharge' => 'required|float|min:0.01',
        ]);

        // Valida se os dados batem com a validação, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        // Obtém o financeiro pelo id
        $financial = Financials::where('id_financial', $request->financial_id);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$financial)
            return response()->json(['message' => 'Financial not found!'], 404);

        // Obtém os valores do financeiro
        $financialPaidAmount = $financial->paid_amount();
        $financialTotalAmount = $financial->total_amount();

        // Se o valor do pagamento mais o valor pago for maior que o total do financeiro, dispara erro
        if ($financialPaidAmount + $request->amount > $financialTotalAmount)
            return response()->json(['message' => 'The payment amount will exceed the total to be paid!'], 403);

        /**
         * Minha intenção é criar uma conexão com um gateway de pagamento aqui, para que antes de criar o
         * pagamento ele valide o pagamento na "vida real", em caso de sucesso, ele cadastra no banco de 
         * dados. Mas por enquanto todo pagamento é autorizado.
         */

        // Cria pagamento
        $atributtes = [
            'financial_id' => $request->financial_id,
            'amount' => $request->amount,
            'method' => $request->method,
            'discount' => $request->discount,
            'surchage' => $request->surchage,
            'sequencial' => $financial->lastPaymentSequencial() + 1
        ];
        $payment = Payments::create($atributtes);

        // Autoriza o pagamento e verifica o financeiro
        $payment->toAuthorize();

        // Obtém o financeiro pelo id
        $financial = Financials::where('id_financial', $request->financial_id);

        // Se o financeiro foi pago, chama o método de conclusão do pedido
        if ($financial->status == 'paid') {
            // Obtém pedido pelo id
            $order = Orders::where('id_order', $financial->order_id);

            // Caso não encontre, dispara mensagem de não encontrado
            if (!$order)
                return response()->json(['message' => 'Order not found!'], 404);

            //Atualiza pedido para status "Completo"
            $order->toComplete();
        }

        // Retorna pagamento com mensagem de sucesso
        return response()->json([
            'message' => 'Payment created successfully!',
            'data' => $payment
        ], 201);
    }
}
