<?php

namespace App\Http\Controllers;

use RunTimeException;
use App\Core\Services\IAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private IAuthService $auth) {}

    public function login()
    {
        return view('auth.login');
    }

    public function redirect(Request $request)
    {
        $login = $this->auth->iniciarLogin(route('auth.callback'));

        $request->session()->put('code_verifier', $login->codeVerifier);

        return redirect()->away($login->url);
    }

    public function callback(Request $request)
    {
        // pull lê e apaga: o verifier só vale para esta tentativa.
        $verifier = $request->session()->pull('code_verifier');

        if ($request->filled('error')) {
            return $this->falha($this->mensagemDoErro((string) $request->query('error_description')));
        }

        $code = $request->query('code');

        if (!$code || !$verifier) {
            return $this->falha('Não foi possível concluir o login. Tente novamente.');
        }

        try {
            $perfil = $this->auth->autenticar($code, $verifier);
        } catch (RunTimeException $e) {
            report($e); // registra em storage/logs/laravel.log
            return $this->falha('Não foi possível concluir o login. Tente novamente.');
        }

        $request->session()->regenerate();
        $request->session()->put('usuario_id', $perfil->id);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function falha(string $mensagem)
    {
        return redirect()->route('login')->withErrors(['login' => $mensagem]);
    }

    private function mensagemDoErro(string $descricao): string
    {
        // O trigger do banco rejeita e-mails fora de usuarios_permitidos.
        if (str_contains($descricao, 'Database error saving new user')) {
            return 'Seu e-mail não está autorizado a acessar o sistema.';
        }

        return 'O login com Google falhou. Tente novamente.';
    }
}