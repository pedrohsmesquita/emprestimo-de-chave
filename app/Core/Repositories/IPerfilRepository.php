<?php

namespace App\Core\Repositories;

use App\Core\Entities\Perfil;

interface IPerfilRepository
{
    public function buscarPorId(string $id): ?Perfil;

    public function buscarPorEmail(string $email): ?Perfil;
}