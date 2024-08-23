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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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

        // Retorna pagamento com mensagem de sucesso
        return response()->json([
            'message' => 'Payment created successfully!',
            'data' => $payment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        //
    }
}
