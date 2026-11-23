<?php

namespace App\Http\Middleware;

use Closure;

/** Site public : http redirigé vers https, cookies envoyés uniquement en https. */
class ForcerHttps
{
    public function handle($request, Closure $next)
    {
        $officiel = preg_replace('/^www\./', '', (string) parse_url(config('app.url'), PHP_URL_HOST));
        $demande = preg_replace('/^www\./', '', $request->getHost());
        $https = parse_url(config('app.url'), PHP_URL_SCHEME) === 'https' && $officiel === $demande;

        if ($https && !$request->isSecure() && $request->isMethodSafe()) {
            return redirect()->to('https://' . $request->getHost() . $request->getRequestUri(), 301);
        }
        config(['session.secure' => $request->isSecure()]);

        return $next($request);
    }
}
