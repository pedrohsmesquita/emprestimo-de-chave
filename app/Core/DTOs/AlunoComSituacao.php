<?php

namespace App\Core\DTOs;

use App\Core\Entities\Perfil;
use App\Core\Entities\Solicitacao;

class AlunoComSituacao
{
    public function __construct(
        public readonly Perfil $aluno,
        public readonly ?Solicitacao $emprestimoAtivo = null,
    ) {}

    public function temChaveEmprestada(): bool
    {
        return $this->emprestimoAtivo !== null;
    }
}