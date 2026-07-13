<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $utilisateur = $request->user();

        if (!$utilisateur || !in_array($utilisateur->role->nom_role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}