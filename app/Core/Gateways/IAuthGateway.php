<?php

namespace App\Core\Gateways;

use App\Core\DTOs\UsuarioAutenticado;
use RunTimeException;

interface IAuthGateway
{
    public function urlAutorizacao(string $redirectTo, string $codeChallenge): string;

    /** @throws RunTimeException*/
    public function trocarCodigo(string $code, string $codeVerifier): UsuarioAutenticado;
}