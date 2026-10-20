<?php

namespace App\Core\Repositories;

use App\Core\Entities\Solicitacao;
use App\Core\Enums\StatusSolicitacao;

interface ISolicitacaoRepository
{
    public function adicionar(Solicitacao $solicitacao): Solicitacao;

    public function buscarPorId(int $id): ?Solicitacao;

    /** @return Solicitacao[] */
    public function buscarPorAluno(string $alunoId): array;

    public function alterar(Solicitacao $solicitacao): Solicitacao;

    public function excluir(int $id): void;

    public function buscarEmprestimoAtivoDoAluno(string $alunoId): ?Solicitacao;

    /** @return Solicitacao[] */
    public function buscarEmprestimosAtivos(): array;
}