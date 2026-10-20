<?php

namespace App\Services;

use App\Core\Entities\Perfil;
use App\Core\Repositories\IPerfilRepository;
use App\Core\Services\IPerfilService;
use App\Core\Enums\Papel;

class PerfilService implements IPerfilService
{
    public function __construct(private IPerfilRepository $repo) {}

    public function buscarPorId(string $id): ?Perfil
    {
        return $this->repo->buscarPorId(trim($id));
    }

    public function buscarPorEmail(string $email): ?Perfil
    {
        return $this->repo->buscarPorEmail(mb_strtolower(trim($email)));
    }

    public function buscarPorPapel(Papel $papel): array
    {
        return $this->repo->buscarPorPapel($papel);
    }
}