<?php

namespace Src\shared\infrastructure\impl\out;

use Illuminate\Support\Facades\Http;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\domain\value_objects\Permission;
use Src\shared\domain\value_objects\User;

class ExtractCurrentUserImpl implements ExtractCurrentUser
{

    public function currentUser(): User
    {
        try {
            $tokenString = request()->bearerToken();

            $user = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $tokenString
            ])->get('http://auth-service-laravel.test-1:80/api/v1/auth/me');

            $user = json_decode($user->body());

            if (!$user) {
                return null;
            }

            $user = new User(
                $user->id,
                $user->email,
                array_map(
                    function ($permission) {
                        return new Permission($permission);
                    },
                    $user->permissions
                )
            );

            return $user;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return null;
        }
    }
}


