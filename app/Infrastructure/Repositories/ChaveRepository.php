<?php

namespace App\Infrastructure\Repositories;

use App\Core\Entities\Chave;
use App\Core\Enums\Disponibilidade;
use App\Core\Repositories\IChaveRepository;
use App\Infrastructure\Supabase\SupabaseClient;

class ChaveRepository implements IChaveRepository
{
    private const TABELA = 'chave';

    public function __construct(private SupabaseClient $supabase) {}

    public function buscarPorId(int $id): ?Chave
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, [
                'id' => "eq.{$id}",
                'limit' => 1,
            ])
            ->json();

        return $linhas ? $this->mapear($linhas[0]) : null;
    }

    public function buscarTodas(): array
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, ['order' => 'nome.asc'])
            ->json();

        return array_map(fn (array $l) => $this->mapear($l), $linhas);
    }

    public function atualizar(Chave $chave): Chave
    {
        $linhas = $this->supabase->request()
            ->withHeaders(['Prefer' => 'return=representation'])
            ->withQueryParameters(['id' => "eq.{$chave->id}"])
            ->patch(self::TABELA, [
                'nome' => $chave->nome,
                'disponibilidade' => $chave->disponibilidade->value,
            ])
            ->json();

        return $this->mapear($linhas[0]);
    }

    private function mapear(array $linha): Chave
    {
        return new Chave(
            $linha['id'],
            $linha['nome'],
            Disponibilidade::from($linha['disponibilidade']),
        );
    }
}