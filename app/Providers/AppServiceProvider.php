<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Supabase\SupabaseClient;

use App\Core\Repositories\IUsuarioPermitidoRepository;
use App\Infrastructure\Repositories\UsuarioPermitidoRepository;

use App\Core\Repositories\IChaveRepository;
use App\Infrastructure\Repositories\ChaveRepository;

use App\Core\Repositories\IPerfilRepository;
use App\Infrastructure\Repositories\PerfilRepository;

use App\Core\Repositories\ISolicitacaoRepository;
use App\Infrastructure\Repositories\SolicitacaoRepository;

use App\Core\Services\IUsuarioPermitidoService;
use App\Services\UsuarioPermitidoService;

use App\Core\Services\IChaveService;
use App\Services\ChaveService;

use App\Core\Services\ISolicitacaoService;
use App\Services\SolicitacaoService;

use App\Core\Services\IPerfilService;
use App\Services\PerfilService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SupabaseClient::class, fn () => new SupabaseClient(
            config('supabase.url'),
            config('supabase.key'),
        ));

        $this->app->bind(
            IUsuarioPermitidoRepository::class,
            UsuarioPermitidoRepository::class
        );

        $this->app->bind(
            IChaveRepository::class,
            ChaveRepository::class
        );

        $this->app->bind(
            IPerfilRepository::class,
            PerfilRepository::class
        );

        $this->app->bind(
            ISolicitacaoRepository::class,
            SolicitacaoRepository::class
        );

        $this->app->bind(
            IUsuarioPermitidoService::class,
            UsuarioPermitidoService::class
        );

        $this->app->bind(
            IChaveService::class,
            ChaveService::class
        );

        $this->app->bind(
            ISolicitacaoService::class,
            SolicitacaoService::class
        );

        $this->app->bind(
            IPerfilService::class,
            PerfilService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
