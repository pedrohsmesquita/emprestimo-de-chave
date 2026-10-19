<?php

namespace App\Infrastructure\Repositories;

use App\Core\Entities\Solicitacao;
use App\Core\Enums\StatusSolicitacao;
use App\Core\Repositories\ISolicitacaoRepository;
use App\Infrastructure\Supabase\SupabaseClient;
use DateTimeImmutable;

class SolicitacaoRepository implements ISolicitacaoRepository
{
    private const TABELA = 'solicitacao';

    public function __construct(private SupabaseClient $supabase) {}

    public function adicionar(Solicitacao $solicitacao): Solicitacao
    {
        $linhas = $this->supabase->request()
            ->withHeaders(['Prefer' => 'return=representation'])
            ->post(self::TABELA, $this->paraArray($solicitacao))
            ->json();

        return $this->mapear($linhas[0]);
    }

    public function buscarPorId(int $id): ?Solicitacao
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, [
                'id' => "eq.{$id}",
                'limit' => 1,
            ])
            ->json();

        return $linhas ? $this->mapear($linhas[0]) : null;
    }

    public function buscarPorStatus(StatusSolicitacao $status): array
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, [
                'status' => "eq.{$status->value}",
                'order' => 'id.asc',
            ])
            ->json();

        return array_map(fn (array $l) => $this->mapear($l), $linhas);
    }

    public function buscarPorAluno(string $alunoId): array
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, [
                'aluno_id' => "eq.{$alunoId}",
                'order' => 'id.desc',
            ])
            ->json();

        return array_map(fn (array $l) => $this->mapear($l), $linhas);
    }

    public function alterar(Solicitacao $solicitacao): Solicitacao
    {
        $linhas = $this->supabase->request()
            ->withHeaders(['Prefer' => 'return=representation'])
            ->withQueryParameters(['id' => "eq.{$solicitacao->id}"])
            ->patch(self::TABELA, $this->paraArray($solicitacao))
            ->json();

        return $this->mapear($linhas[0]);
    }

    public function excluir(int $id): void
    {
        $this->supabase->request()
            ->withQueryParameters(['id' => "eq.{$id}"])
            ->delete(self::TABELA);
    }

    private function paraArray(Solicitacao $s): array
    {
        return [
            'chave_id' => $s->chaveId,
            'aluno_id' => $s->alunoId,
            'autorizado_por_id' => $s->autorizadoPorId,
            'status' => $s->status->value,
            'retirada_em' => $s->retiradaEm?->format(DATE_ATOM),
            'devolvida_em' => $s->devolvidaEm?->format(DATE_ATOM),
        ];
    }

    private function mapear(array $linha): Solicitacao
    {
        return new Solicitacao(
            $linha['id'],
            $linha['chave_id'],
            $linha['aluno_id'],
            $linha['autorizado_por_id'],
            StatusSolicitacao::from($linha['status']),
            $linha['retirada_em'] ? new DateTimeImmutable($linha['retirada_em']) : null,
            $linha['devolvida_em'] ? new DateTimeImmutable($linha['devolvida_em']) : null,
        );
    }
}