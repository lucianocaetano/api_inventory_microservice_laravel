<?php

namespace Src\product\application\contracts\in;

interface FindAllProductsUseCasePort {

    public function execute(array $filter): array;
}
