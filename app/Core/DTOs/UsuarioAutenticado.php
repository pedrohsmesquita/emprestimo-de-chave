<?php

namespace App\Core\DTOs;

class UsuarioAutenticado
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
    ) {}
}