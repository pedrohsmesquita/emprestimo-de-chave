<?php

namespace App\Infrastructure\Repositories;

use App\Core\Entities\Perfil;
use App\Core\Enums\Papel;
use App\Core\Repositories\IPerfilRepository;
use App\Infrastructure\Supabase\SupabaseClient;

class PerfilRepository implements IPerfilRepository
{
    private const TABELA = 'perfil';

    public function __construct(private SupabaseClient $supabase) {}

    public function buscarPorId(string $id): ?Perfil
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, [
                'id' => "eq.{$id}",
                'limit' => 1,
            ])
            ->json();

        return $linhas ? $this->mapear($linhas[0]) : null;
    }

    public function buscarPorEmail(string $email): ?Perfil
    {
        $linhas = $this->supabase->request()
            ->get(self::TABELA, [
                'email' => "eq.{$email}",
                'limit' => 1,
            ])
            ->json();

        return $linhas ? $this->mapear($linhas[0]) : null;
    }

    private function mapear(array $linha): Perfil
    {
        return new Perfil(
            $linha['id'],
            $linha['email'],
            $linha['nome'],
            Papel::from($linha['papel']),
        );
    }
}