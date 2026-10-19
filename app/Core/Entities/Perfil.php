<?php

namespace App\Core\Entities;

use App\Core\Enums\Papel;

class Perfil
{
    public function __construct(
        public readonly string $id,
        public string $email,
        public string $nome,
        public Papel $papel,
    ) {}
}