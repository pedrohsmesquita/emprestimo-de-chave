<?php

namespace App\Services;

use App\Core\DTOs\LoginIniciado;
use App\Core\Entities\Perfil;
use App\Core\Gateways\IAuthGateway;
use App\Core\Repositories\IPerfilRepository;
use App\Core\Services\IAuthService;
use RunTimeException;

class AuthService implements IAuthService
{
    public function __construct(
        private IAuthGateway $gateway,
        private IPerfilRepository $perfis,
    ) {}

    public function iniciarLogin(string $redirectTo): LoginIniciado
    {
        $verifier = $this->base64Url(random_bytes(64));
        $challenge = $this->base64Url(hash('sha256', $verifier, true));

        return new LoginIniciado(
            $this->gateway->urlAutorizacao($redirectTo, $challenge),
            $verifier,
        );
    }

    public function autenticar(string $code, string $codeVerifier): Perfil
    {
        $usuario = $this->gateway->trocarCodigo($code, $codeVerifier);

        $perfil = $this->perfis->buscarPorId($usuario->id);

        if ($perfil === null) {
            throw new RunTimeException('Perfil não encontrado para este usuário.');
        }

        return $perfil;
    }

    private function base64Url(string $binario): string
    {
        return rtrim(strtr(base64_encode($binario), '+/', '-_'), '=');
    }
}