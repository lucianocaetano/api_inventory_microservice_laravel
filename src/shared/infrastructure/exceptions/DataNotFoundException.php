<?php

namespace Src\shared\infrastructure\exceptions;

class DataNotFoundException extends \Exception {

    public function __construct(string $entity = 'Data') {
        parent::__construct($entity . ' not found');
    }
}
