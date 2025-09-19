<?php

namespace Src\payment\application\contracts\out;

interface PaymentReadRepository
{
    public function list(array $filters): array;
}
