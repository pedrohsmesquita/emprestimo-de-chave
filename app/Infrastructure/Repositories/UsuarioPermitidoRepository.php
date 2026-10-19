<?php

namespace App\Infrastructure\Repositories;

use App\Core\Repositories\IUsuarioPermitidoRepository;
use App\Core\Entities\UsuarioPermitido;
use App\Core\Enums\Papel;
use App\Infrastructure\Supabase\SupabaseClient;

class UsuarioPermitidoRepository implements IUsuarioPermitidoRepository
{
    private const TABELA = 'usuarios_permitidos';

    public function __construct(private SupabaseClient $supabase) {}

    public function adicionar(UsuarioPermitido $usuario): UsuarioPermitido
    {
        $linhas = $this->supabase->request()
            ->withHeaders(['Prefer' => 'return=representation'])
            ->post(self::TABELA, [
                'email' => $usuario->email,
                'nome' => $usuario->nome,
                'papel' => $usuario->papel->value,
            ])
            ->json();

        return $this->mapear($linhas[0]);
    }

    public function excluir(string $email): void
    {
        $this->supabase->request()
            ->withQueryParameters(['email' => "eq.{$email}"])
            ->delete(self::TABELA);
    }

    public function buscarPorEmail(string $email): ?UsuarioPermitido
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, [
                'email' => "eq.{$email}",
                'limit' => 1,
            ])
            ->json();

        return $linhas ? $this->mapear($linhas[0]) : null;
    }

    public function buscarTodos(): array
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, ['order' => 'nome.asc'])
            ->json();

        return array_map(fn (array $l) => $this->mapear($l), $linhas);
    }

    private function mapear(array $linha): UsuarioPermitido
    {
        return new UsuarioPermitido(
            $linha['email'],
            $linha['nome'],
            Papel::from($linha['papel']),
        );
    }
}