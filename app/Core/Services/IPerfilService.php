<?php

namespace App\Core\Services;

use App\Core\Entities\Perfil;

interface IPerfilService
{
    public function buscarPorId(string $id): ?Perfil;

    public function buscarPorEmail(string $email): ?Perfil;
}