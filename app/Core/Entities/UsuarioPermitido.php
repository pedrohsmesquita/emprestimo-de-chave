<?php

namespace App\Core\Entities;

use App\Core\Enums\Papel;

class UsuarioPermitido
{
    public function __construct(
        public readonly string $email,
        public string $nome,
        public Papel $papel,
    ) {}
}