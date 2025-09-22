<?php

namespace Src\shared\infrastructure\impl\out;

use Illuminate\Support\Facades\Log;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\domain\value_objects\Permission;
use Src\shared\domain\value_objects\User;

class ExtractCurrentUserImpl implements ExtractCurrentUser
{

    public function currentUser(): ?User
    {
        try {
            $tokenString = request()->user();

            if (!$tokenString) {
                return null;
            }

            $user = new User(
                $tokenString['id'],
                $tokenString['email'],
                array_map(
                    function ($permission) {
                        return new Permission($permission);
                    },
                    $tokenString['permissions']
                )
            );

            return $user;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}


