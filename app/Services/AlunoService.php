<?php

namespace App\Services;

use App\Core\DTOs\AlunoComSituacao;
use App\Core\Enums\Papel;
use App\Core\Repositories\IPerfilRepository;
use App\Core\Repositories\ISolicitacaoRepository;
use App\Core\Services\IAlunoService;

class AlunoService implements IAlunoService
{
    public function __construct(
        private IPerfilRepository $perfis,
        private ISolicitacaoRepository $solicitacoes,
    ) {}

    /** @return AlunoComSituacao[] */
    public function listarAlunosComSituacao(): array
    {
        $alunos = $this->perfis->buscarPorPapel(Papel::Aluno);

        $emprestimosPorAluno = [];
        foreach ($this->solicitacoes->buscarEmprestimosAtivos() as $emprestimo) {
            $emprestimosPorAluno[$emprestimo->alunoId] = $emprestimo;
        }

        return array_map(
            fn ($aluno) => new AlunoComSituacao($aluno, $emprestimosPorAluno[$aluno->id] ?? null),
            $alunos
        );
    }
}