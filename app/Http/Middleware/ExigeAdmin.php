<?php

namespace App\Http\Middleware;

use App\Core\Entities\Perfil;
use App\Core\Enums\Papel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExigeAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Perfil|null $perfil */
        $perfil = $request->attributes->get('perfil');

        if ($perfil === null || $perfil->papel !== Papel::Admin) {
            abort(403, 'Acesso restrito ao administrador.');
        }

        return $next($request);
    }
}