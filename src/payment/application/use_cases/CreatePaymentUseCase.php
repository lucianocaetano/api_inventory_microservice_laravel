<?php

namespace Src\payment\application\use_cases;

use Src\payment\domain\entities\Payment;
use Src\payment\domain\repositories\PaymentRepository;

class CreatePaymentUseCase {

    public function __construct(
        private PaymentRepository $repository
    ) {}

    public function execute(Payment $payment) {

        return $this->repository->create($payment);
    }
}
