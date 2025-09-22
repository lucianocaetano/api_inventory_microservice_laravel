<?php

namespace Src\shared\infrastructure\middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ValidTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->bearerToken();

        if(!$token) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $res = Http::asForm()->withBasicAuth(
            env('KEYCLOAK_CLIENT_ID'),
            env('KEYCLOAK_CLIENT_SECRET')
        )->post(env('KEYCLOAK_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/token/introspect', [
            'token' => $token
        ]);

        if($res->status() == 200) {

            $data = $res->json();

            $active = filter_var($data['active'], FILTER_VALIDATE_BOOLEAN);

            if (!$active) {
                return response()->json(['error' => 'Invalid token'], 401);
            }
        } else {

            return response()->json(['error' => 'Invalid token'], 401);
        }

        $request->setUserResolver(function () use ($data) {

            return [
                'id' => $data['sub'],
                'email' => $data['email'],
                'permissions' => $data['resource_access']['microservicios']['roles'] ?? []
            ];
        });

        return $next($request);
    }
}
