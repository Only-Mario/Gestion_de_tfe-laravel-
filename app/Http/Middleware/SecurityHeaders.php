<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $headers = $response->headers;

        if (!headers_sent()) {
            header_remove('X-Powered-By');
        }
        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        if ($request->isSecure()) {
            $headers->set('Strict-Transport-Security', 'max-age=15552000');
        }
        // Aucun script en ligne : un contenu malveillant enregistré ne peut pas s'exécuter.
        $headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ]));
        // Pages personnelles : jamais en cache
        if ($request->user()) {
            $headers->set('Cache-Control', 'no-store, private');
        }

        return $response;
    }
}
