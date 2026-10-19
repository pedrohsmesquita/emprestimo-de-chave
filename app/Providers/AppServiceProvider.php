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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
