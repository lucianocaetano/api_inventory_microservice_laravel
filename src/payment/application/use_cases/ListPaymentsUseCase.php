<?php

namespace Src\payment\application\use_cases;

use Src\payment\application\contracts\out\PaymentReadRepository;
use Src\payment\domain\service\PaymentService;
use Src\shared\application\contracts\out\ExtractCurrentUser;

class ListPaymentsUseCase {

    public function __construct(
        private PaymentReadRepository $repository,
        private ExtractCurrentUser $extractCurrentUser
    ) {}

    public function execute(array $filers)
    {

        $user = $this->extractCurrentUser->currentUser();

        $service = new PaymentService($user->permissions());

        $service->validList();

        return $this->repository->list($filers);
    }
}
