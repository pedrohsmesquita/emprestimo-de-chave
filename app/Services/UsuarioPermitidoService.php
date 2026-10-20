<?php

namespace App\Services;

use App\Core\Entities\UsuarioPermitido;
use App\Core\Enums\Papel;
use App\Core\Repositories\IUsuarioPermitidoRepository;
use App\Core\Services\IUsuarioPermitidoService;
use InvalidArgumentException;

class UsuarioPermitidoService implements IUsuarioPermitidoService
{
    public function __construct(private IUsuarioPermitidoRepository $repo) {}

    public function adicionarUsuario(string $email, string $nome, Papel $papel): UsuarioPermitido
    {
        $email = mb_strtolower(trim($email));
        $nome = trim($nome);

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('E-mail inválido.');
        }

        if ($nome === '') {
            throw new InvalidArgumentException('O nome é obrigatório.');
        }

        if ($this->repo->buscarPorEmail($email) !== null) {
            throw new InvalidArgumentException('Este e-mail já está cadastrado.');
        }

        return $this->repo->adicionar(new UsuarioPermitido($email, $nome, $papel));
    }

    public function removerUsuario(string $email): void
    {
        $email = mb_strtolower(trim($email));

        if ($this->repo->buscarPorEmail($email) === null) {
            throw new InvalidArgumentException('Usuário não encontrado.');
        }

        $this->repo->excluir($email);
    }

    /** @return UsuarioPermitido[] */
    public function obterTodosUsuarios(): array
    {
        return $this->repo->buscarTodos();
    }
}