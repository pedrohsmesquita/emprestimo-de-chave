<?php

namespace App\Core\Services;

use App\Core\DTOs\AlunoComSituacao;

interface IAlunoService
{
    /** @return AlunoComSituacao[] */
    public function listarAlunosComSituacao(): array;
}