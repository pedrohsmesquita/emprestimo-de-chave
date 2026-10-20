<?php

use Illuminate\Support\Facades\Route;
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::middleware('logado')->group(function () {
    Route::get('/', fn () => 'Olá, ' . request()->attributes->get('perfil')->nome);

    // rotas do aluno entram aqui

    Route::middleware('admin')->group(function () {
        Route::get('/admin', fn () => 'Área do administrador');

        // rotas do técnico entram aqui
    });
});


use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');