<?php

namespace App\Http\Middleware;

use App\Core\Services\IPerfilService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExigeLogin
{
    public function __construct(private IPerfilService $perfis) {}

    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->session()->get('usuario_id');

        if (!$id) {
            return redirect()->route('login');
        }

        $perfil = $this->perfis->buscarPorId($id);

        if ($perfil === null) {
            // Sessão aponta para um perfil que não existe mais.
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        $request->attributes->set('perfil', $perfil);

        return $next($request);
    }
}