<?php

namespace App\Core\Services;

use App\Core\Entities\Solicitacao;
use App\Core\Enums\StatusSolicitacao;

interface ISolicitacaoService
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

    public function aprovar(int $solicitacaoId, string $adminId): Solicitacao;

    public function devolver(int $solicitacaoId): Solicitacao;
}