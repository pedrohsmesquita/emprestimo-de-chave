<?php

namespace App\Infrastructure\Supabase;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SupabaseClient
{
    public function __construct(
        private string $url,
        private string $key,
    ) {}

    public function request(): PendingRequest
    {
        return Http::baseUrl("{$this->url}/rest/v1")
            ->withHeaders([
                'apikey' => $this->key,
                'Authorization' => "Bearer {$this->key}",
            ])
            ->acceptJson()
            ->asJson()
            ->throw();
    }

    public function authRequest(): PendingRequest
    {
        return Http::baseUrl("{$this->url}/auth/v1")
            ->withHeaders(['apikey' => $this->key])
            ->acceptJson()
            ->asJson();
    }
}