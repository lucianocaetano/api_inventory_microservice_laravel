<?php

namespace Tests\helpers;

use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

class authentication {

    public static function getNormalToken (): ?string {
        $provider = new Keycloak([
            'authServerUrl' => env('KEYCLOAK_URL'),
            'realm' => env('KEYCLOAK_REALM'),
            'clientId' => env('KEYCLOAK_CLIENT_ID'),
            'clientSecret' => env('KEYCLOAK_CLIENT_SECRET'),
            'redirectUri' => '',
        ]);

        try {
            $token = $provider->getAccessToken('password', [
                'username' => 'normal@gmail.com',
                'password' => 'password',
            ]);

            return $token->getToken();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getSuperToken(): ?string
    {
        try {
            $provider = new Keycloak([
                'authServerUrl' => env('KEYCLOAK_URL'),
                'realm' => env('KEYCLOAK_REALM'),
                'clientId' => env('KEYCLOAK_CLIENT_ID'),
                'clientSecret' => env('KEYCLOAK_CLIENT_SECRET'),
                'redirectUri' => '',
            ]);

            $token = $provider->getAccessToken('password', [
                'username' => 'admin@gmail.com',
                'password' => 'admin',
            ]);

            return $token->getToken();
        } catch (\Exception $e) {
            return null;
        }
    }
}
