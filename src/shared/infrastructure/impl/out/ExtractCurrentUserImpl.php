<?php

namespace Src\shared\infrastructure\impl\out;

use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Configuration;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\domain\value_objects\Permission;
use Src\shared\domain\value_objects\User;

class ExtractCurrentUserImpl implements ExtractCurrentUser
{

    public function currentUser(): ?User
    {
        try {
            $tokenString = request()->bearerToken();
            $config = Configuration::forUnsecuredSigner();

            $token = $config->parser()->parse($tokenString);

            $claims = $token->claims()->all();

            if (!$tokenString) {
                return null;
            }

            $user = new User(
                $claims['sub'],
                $claims['email'],
                array_map(
                    function ($permission) {
                        return new Permission($permission);
                    },
                    $claims['resource_access']['microservicios']['roles']
                )
            );

            return $user;
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error($e->getMessage());
            return null;
        }
    }
}


