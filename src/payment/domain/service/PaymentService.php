<?php

namespace Src\payment\domain\service;

use Src\payment\domain\repository\PaymentRepository;
use Src\role\domain\exception\InvalidPermission;
use Src\role\domain\value_objects\Permission;

class PaymentService
{
    private $repository;

    /**
     * @param PaymentRepository $repository
     */
    public function __construct(
        private array $permissions
    ) {}

    private function hasPermission(Permission $currentPermission): bool
    {
        foreach ($this->permissions as $thisPermission) {
            if ($thisPermission->equals($currentPermission)) {
                return true;
            }
        }
        return false;
    }

    public function validList()
    {
        $isValid = $this->hasPermission(new Permission('payment_list'));

        if (!$isValid) {
            throw new InvalidPermission('You do not have permission to list payments');
        }
        return $this->repository->list();
    }
}
