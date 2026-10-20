<?php

namespace App\Infrastructure\Supabase;

use App\Core\DTOs\UsuarioAutenticado;
use App\Core\Gateways\IAuthGateway;
use RunTimeException;

class SupabaseAuthGateway implements IAuthGateway
{
    public function __construct(private SupabaseClient $supabase) {}

    public function urlAutorizacao(string $redirectTo, string $codeChallenge): string
    {
        $query = http_build_query([
            'provider' => 'google',
            'redirect_to' => $redirectTo,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 's256',
        ]);

        return rtrim(config('supabase.url'), '/') . "/auth/v1/authorize?{$query}";
    }

    public function trocarCodigo(string $code, string $codeVerifier): UsuarioAutenticado
    {
        $resposta = $this->supabase->authRequest()
            ->post('token?grant_type=pkce', [
                'auth_code' => $code,
                'code_verifier' => $codeVerifier,
            ]);

        if ($resposta->failed()) {
            throw new RunTimeException(
                'Falha ao trocar o código: ' . $resposta->body()
            );
        }

        $usuario = $resposta->json('user');

        if (!isset($usuario['id'], $usuario['email'])) {
            throw new RunTimeException('Resposta de autenticação inesperada.');
        }

        return new UsuarioAutenticado($usuario['id'], mb_strtolower($usuario['email']));
    }
}