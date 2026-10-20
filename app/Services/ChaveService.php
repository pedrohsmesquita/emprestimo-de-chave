<?php

namespace App\Services;

use App\Core\Entities\Chave;
use App\Core\Enums\Disponibilidade;
use App\Core\Repositories\IChaveRepository;
use App\Core\Services\IChaveService;
use InvalidArgumentException;

class ChaveService implements IChaveService
{
    public function __construct(private IChaveRepository $repo) {}

    /** @return Chave[] */
    public function buscarChaves(): array
    {
        return $this->repo->buscarTodas();
    }

    public function buscarChavePorId(int $id): ?Chave
    {
        return $this->repo->buscarPorId($id);
    }

    public function atualizarChave(int $id, string $nome, Disponibilidade $disponibilidade): Chave
    {
        $nome = trim($nome);

        if ($nome === '') {
            throw new InvalidArgumentException('O nome da chave é obrigatório.');
        }

        $chave = $this->repo->buscarPorId($id);

        if ($chave === null) {
            throw new InvalidArgumentException('Chave não encontrada.');
        }

        $chave->nome = $nome;
        $chave->disponibilidade = $disponibilidade;

        return $this->repo->atualizar($chave);
    }
}