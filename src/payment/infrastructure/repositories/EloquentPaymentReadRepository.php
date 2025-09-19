<?php

namespace Src\payment\infrastructure\repositories;

use Src\payment\application\contracts\out\PaymentReadRepository;
use Src\payment\infrastructure\models\Payment;

class EloquentPaymentReadRepository implements PaymentReadRepository {

    public function list(array $filters): array
    {
        $payments = Payment::paginate();

        return [
            "items" => $payments->items(),
            "meta" => [
                "total" => $payments->total(),
                "last_page" => $payments->lastPage(),
                "current_page" => $payments->currentPage()
            ]
        ];
    }
}
