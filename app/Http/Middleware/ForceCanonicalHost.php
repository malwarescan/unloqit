<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceCanonicalHost
{
    /**
     * Handle an incoming request.
     * 
     * Forces canonical host (www.unloqit.com) and redirects non-canonical hosts
     */
    public function handle(Request $request, Closure $next): Response
    {
        $canonicalHost = 'www.unloqit.com';
        $host = $request->getHost();

        // Redirect non-canonical hosts to canonical
        if ($host !== $canonicalHost) {
            $scheme = $request->getScheme();
            $uri = $request->getRequestUri();
            $canonicalUrl = $scheme . '://' . $canonicalHost . $uri;
            return redirect()->to($canonicalUrl, 301);
        }

        return $next($request);
    }
}
