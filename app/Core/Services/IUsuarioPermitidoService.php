<?php

namespace App\Core\Services;

use App\Core\Entities\UsuarioPermitido;
use App\Core\Enums\Papel;

interface IUsuarioPermitidoService
{
    public function adicionarUsuario(string $email, string $nome, Papel $papel): UsuarioPermitido;

    public function removerUsuario(string $email): void;

    /** @return UsuarioPermitido[] */
    public function obterTodosUsuarios(): array;
}