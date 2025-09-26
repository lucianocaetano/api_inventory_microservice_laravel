<?php

namespace Src\category\application\contracts\in;

use Src\shared\domain\value_objects\Id;

interface DeleteProductUseCasePort
{
    public function execute(Id $id): void;
}
