<?php

namespace App\Core\Services;

use App\Core\DTOs\LoginIniciado;
use App\Core\Entities\Perfil;
use RunTimeException;

interface IAuthService
{
    public function iniciarLogin(string $redirectTo): LoginIniciado;

    /** @throws RunTimeException */
    public function autenticar(string $code, string $codeVerifier): Perfil;
}