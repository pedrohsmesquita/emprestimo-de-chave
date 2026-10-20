<?php

namespace App\Services;

use App\Core\Entities\Solicitacao;
use App\Core\Enums\Disponibilidade;
use App\Core\Enums\StatusSolicitacao;
use App\Core\Repositories\IChaveRepository;
use App\Core\Repositories\ISolicitacaoRepository;
use App\Core\Services\ISolicitacaoService;
use InvalidArgumentException;

class SolicitacaoService implements ISolicitacaoService
{
    public function __construct(
        private ISolicitacaoRepository $solicitacoes,
        private IChaveRepository $chaves,
    ) {}

    public function adicionar(Solicitacao $solicitacao): Solicitacao
    {
        $chave = $this->chaves->buscarPorId($solicitacao->chaveId);

        if ($chave === null) {
            throw new InvalidArgumentException('Chave não encontrada.');
        }

        if ($chave->disponibilidade !== Disponibilidade::Disponivel) {
            throw new InvalidArgumentException('Esta chave não está disponível.');
        }

        if ($this->solicitacoes->buscarEmprestimoAtivoDoAluno($solicitacao->alunoId) !== null) {
            throw new InvalidArgumentException('O aluno já está com uma chave emprestada.');
        }

        $nova = new Solicitacao(
            id: null,
            chaveId: $solicitacao->chaveId,
            alunoId: $solicitacao->alunoId,
        );

        return $this->solicitacoes->adicionar($nova);
    }

    public function buscarPorId(int $id): ?Solicitacao
    {
        return $this->solicitacoes->buscarPorId($id);
    }

    /** @return Solicitacao[] */
    public function buscarPorAluno(string $alunoId): array
    {
        return $this->solicitacoes->buscarPorAluno($alunoId);
    }

    public function alterar(Solicitacao $solicitacao): Solicitacao
    {
        if ($solicitacao->id === null) {
            throw new InvalidArgumentException('Solicitação sem id não pode ser alterada.');
        }

        $atual = $this->solicitacoes->buscarPorId($solicitacao->id);

        if ($atual === null) {
            throw new InvalidArgumentException('Solicitação não encontrada.');
        }

        $this->validarTransicao($atual->status, $solicitacao->status);

        $salva = $this->solicitacoes->alterar($solicitacao);

        if ($atual->status !== $salva->status) {
            match ($salva->status) {
                StatusSolicitacao::Aprovada => $this->atualizarDisponibilidade($salva->chaveId, Disponibilidade::Emprestado),
                StatusSolicitacao::Devolvida => $this->atualizarDisponibilidade($salva->chaveId, Disponibilidade::Disponivel),
                default => null,
            };
        }

        return $salva;
    }

    public function excluir(int $id): void
    {
        $solicitacao = $this->solicitacoes->buscarPorId($id);

        if ($solicitacao === null) {
            throw new InvalidArgumentException('Solicitação não encontrada.');
        }

        if ($solicitacao->status === StatusSolicitacao::Aprovada) {
            throw new InvalidArgumentException('Não é possível excluir um empréstimo em andamento. Devolva a chave primeiro.');
        }

        $this->solicitacoes->excluir($id);
    }

    public function buscarEmprestimoAtivoDoAluno(string $alunoId): ?Solicitacao
    {
        return $this->solicitacoes->buscarEmprestimoAtivoDoAluno($alunoId);
    }

    /** @return Solicitacao[] */
    public function buscarEmprestimosAtivos(): array
    {
        return $this->solicitacoes->buscarEmprestimosAtivos();
    }

    private function validarTransicao(StatusSolicitacao $de, StatusSolicitacao $para): void
    {
        if ($de === $para) {
            return;
        }

        $permitido = match ($de) {
            StatusSolicitacao::Pendente => $para === StatusSolicitacao::Aprovada,
            StatusSolicitacao::Aprovada => $para === StatusSolicitacao::Devolvida,
            StatusSolicitacao::Devolvida => false,
        };

        if (!$permitido) {
            throw new InvalidArgumentException("Transição de status inválida: {$de->value} → {$para->value}.");
        }
    }

    private function atualizarDisponibilidade(int $chaveId, Disponibilidade $disponibilidade): void
    {
        $chave = $this->chaves->buscarPorId($chaveId);

        if ($chave === null) {
            throw new InvalidArgumentException('Chave não encontrada.');
        }

        $chave->disponibilidade = $disponibilidade;
        $this->chaves->atualizar($chave);
    }

    public function aprovar(int $solicitacaoId, string $adminId): Solicitacao
    {
        $solicitacao = $this->solicitacoes->buscarPorId($solicitacaoId);

        if ($solicitacao === null) {
            throw new InvalidArgumentException('Solicitação não encontrada.');
        }

        if ($solicitacao->status !== StatusSolicitacao::Pendente) {
            throw new InvalidArgumentException('Apenas solicitações pendentes podem ser aprovadas.');
        }

        $chave = $this->chaves->buscarPorId($solicitacao->chaveId);

        if ($chave === null) {
            throw new InvalidArgumentException('Chave não encontrada.');
        }

        if ($chave->disponibilidade !== Disponibilidade::Disponivel) {
            throw new InvalidArgumentException('Esta chave não está disponível.');
        }

        $solicitacao->autorizadoPorId = $adminId;
        $solicitacao->status = StatusSolicitacao::Aprovada;
        $solicitacao->retiradaEm = new DateTimeImmutable();

        return $this->alterar($solicitacao);
    }

    public function devolver(int $solicitacaoId): Solicitacao
    {
        $solicitacao = $this->solicitacoes->buscarPorId($solicitacaoId);

        if ($solicitacao === null) {
            throw new InvalidArgumentException('Solicitação não encontrada.');
        }

        if ($solicitacao->status !== StatusSolicitacao::Aprovada) {
            throw new InvalidArgumentException('Apenas empréstimos em andamento podem ser devolvidos.');
        }

        $solicitacao->status = StatusSolicitacao::Devolvida;
        $solicitacao->devolvidaEm = new DateTimeImmutable();

        return $this->alterar($solicitacao);
    }
}