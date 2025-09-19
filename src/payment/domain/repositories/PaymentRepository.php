<?php

namespace Src\payment\domain\repositories;

use Src\payment\domain\entities\Payment;

interface PaymentRepository {

    public function create(Payment $payment): Payment;
    public function update(Payment $payment): Payment;
    public function delete();
}
