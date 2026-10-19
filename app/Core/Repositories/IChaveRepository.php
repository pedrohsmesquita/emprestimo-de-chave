<?php

namespace App\Core\Contracts\Repositories;

use App\Core\Entities\Chave;

interface IChaveRepository
{
    public function buscarPorId(int $id): ?Chave;

    /** @return Chave[] */
    public function buscarTodas(): array;

    public function atualizar(Chave $chave): Chave;
}