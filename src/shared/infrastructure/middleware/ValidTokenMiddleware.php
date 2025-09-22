<?php

namespace Src\shared\infrastructure\middleware;

use Closure;
use DateTimeZone;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;use Illuminate\Http\Request;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
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

        $jwk = json_decode(file_get_contents(env('KEYCLOAK_URL').'/realms/'.env('KEYCLOAK_REALM').'/protocol/openid-connect/certs'), true);

        $publicKey = InMemory::plainText($jwk['keys'][0]['x5c'][0]);

        $config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::empty(),
            $publicKey
        );


        $token = $config->parser()->parse($token);

        $clock = new SystemClock(new DateTimeZone('UTC'));

        $config->validator()->assert(
            $token,
            new SignedWith($config->signer(), $config->verificationKey()),
            new ValidAt($clock)
        );
        return $next($request);
    }
}
