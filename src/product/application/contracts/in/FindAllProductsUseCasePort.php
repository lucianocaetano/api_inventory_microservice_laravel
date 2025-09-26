<?php

namespace Src\category\application\contracts\in;

interface FindAllProductsUseCasePort {

    public function execute(array $filter): array;
}
