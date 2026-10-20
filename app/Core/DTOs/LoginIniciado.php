<?php

namespace App\Core\DTOs;

class LoginIniciado
{
    public function __construct(
        public readonly string $url,
        public readonly string $codeVerifier,
    ) {}
}