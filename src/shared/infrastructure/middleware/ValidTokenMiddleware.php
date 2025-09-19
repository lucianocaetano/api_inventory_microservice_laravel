<?php

namespace Src\shared\infrastructure\middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ValidTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();

            $request->setUserResolver(function () use ($payload) {
                return (object) [
                    'id' => $payload->get('sub'),
                    'user' => $payload->get('user'),
                ];
            });

        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 301);
        }

        return $next($request);
    }
}
