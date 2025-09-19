<?php

namespace Src\payment\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\payment\application\use_cases\CreatePaymentUseCase;
use Src\payment\application\use_cases\ListPaymentsUseCase;
use Src\payment\domain\entities\Payment;
use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Currency;

class PaymentController extends Controller {

    public function __construct(
        private ListPaymentsUseCase $listPaymentsUseCase,
        private CreatePaymentUseCase $createPaymentUseCase
    ) {}

    public function index(Request $request) {
        $filters = $request->all();

        return $this->listPaymentsUseCase->execute($filters);
    }

    public function create(Request $request) {

        $data = $request->validate([
            'order_id' => 'required',
            'client_id' => 'required',
            'amount' => 'required|numeric',
            'currency_code' => 'required|string',
            'currency_symbol' => 'required|string',
            'currency_decimals' => 'required|numeric',
        ]);


        $payment = new Payment(
            $data['id'],
            $data['order_id'],
            $data['client_id'],
            $data['status'],
            new Amount(
                $data['amount'],
                new Currency(
                    $request->input('currency_code'),
                    $request->input('currency_symbol'),
                    $request->input('currency_decimals')
                )
            )
        );

        return $this->createPaymentUseCase->execute($payment);
    }
}
