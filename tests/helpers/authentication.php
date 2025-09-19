<?php

namespace Tests\helpers;

use Illuminate\Support\Facades\Http;

class authentication {

    public static function getNormalToken () {

        $res = Http::post(
            'http://auth-service-laravel.test-1:80/api/v1/auth/register',
            [
                'name' => 'new_user',
                'email' => 'new_user@example.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ]
        );

        if($res->status() !== 201 || $res->status() !== 200) {

            $res = Http::post(
                'http://auth-service-laravel.test-1:80/api/v1/auth/login',
                [
                    'email' => 'new_user@example.com',
                    'password' => 'password',
                ]
            );

            return json_decode($res->body())->access_token;
        } else {
            return json_decode($res->body())->access_token;
        }
    }

    public static function getSuperToken(): string
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json'
        ])->post(
            'http://auth-service-laravel.test-1:80/api/v1/auth/login',
            [
                "email" => "test@example.com",
                "password" => "password",
            ]
        );

        $token = json_decode($res->body())->access_token;

        return $token;
    }
}
