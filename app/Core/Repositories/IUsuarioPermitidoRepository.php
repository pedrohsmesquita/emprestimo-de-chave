<?php

namespace App\Core\Contracts\Repositories;

use App\Core\Entities\UsuarioPermitido;

interface IUsuarioPermitidoRepository
{
    public function adicionar(UsuarioPermitido $usuario): UsuarioPermitido;

    public function excluir(string $email): void;

    public function buscarPorEmail(string $email): ?UsuarioPermitido;

    /** @return UsuarioPermitido[] */
    public function buscarTodos(): array;
}