<?php

namespace App\Core\Repositories;

use App\Core\Entities\Perfil;
use App\Core\Enums\Papel;

interface IPerfilRepository
{
    public function buscarPorId(string $id): ?Perfil;

    public function buscarPorEmail(string $email): ?Perfil;

    /** @return Perfil[] */
    public function buscarPorPapel(Papel $papel): array;
}