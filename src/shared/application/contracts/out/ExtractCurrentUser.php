<?php

namespace  Src\shared\application\contracts\out;

use Src\shared\domain\value_objects\User;

interface ExtractCurrentUser {

    public function currentUser(): User;
}
