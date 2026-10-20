<?php

namespace App\Core\Services;

use App\Core\Entities\Chave;
use App\Core\Enums\Disponibilidade;

interface IChaveService
{
    /** @return Chave[] */
    public function buscarChaves(): array;

    public function buscarChavePorId(int $id): ?Chave;

    public function atualizarChave(int $id, string $nome, Disponibilidade $disponibilidade): Chave;
}