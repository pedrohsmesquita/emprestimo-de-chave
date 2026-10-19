<?php

namespace App\Core\Entities;

use App\Core\Enums\Disponibilidade;

class Chave
{
    public function __construct(
        public readonly ?int $id,
        public string $nome,
        public Disponibilidade $disponibilidade = Disponibilidade::Disponivel,
    ) {}
}