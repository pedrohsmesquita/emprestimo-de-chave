<?php

namespace App\Core\Entities;

use App\Core\Enums\StatusSolicitacao;
use DateTimeImmutable;

class Solicitacao
{
    public function __construct(
        public readonly ?int $id,
        public int $chaveId,
        public string $alunoId,
        public ?string $autorizadoPorId = null,
        public StatusSolicitacao $status = StatusSolicitacao::Pendente,
        public ?DateTimeImmutable $retiradaEm = null,
        public ?DateTimeImmutable $devolvidaEm = null,
    ) {}
}