<?php

namespace App\Core\Services;

use App\Core\Entities\Perfil;
use App\Core\Enums\Papel;

interface IPerfilService
{
    public function buscarPorId(string $id): ?Perfil;

    public function buscarPorEmail(string $email): ?Perfil;

    /** @return Perfil[] */
    public function buscarPorPapel(Papel $papel): array;
}