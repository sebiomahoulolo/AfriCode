<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MonerooService
{
    private const BASE_URL = 'https://api.moneroo.io/v1';
    private const TIMEOUT  = 30;

    private string $secretKey;

    public function __construct()
    {
        $key = config('moneroo.secretKey');

        if (empty($key) || ! is_string($key)) {
            throw new \RuntimeException('MONEROO_SECRET_KEY manquant ou invalide dans .env');
        }

        $this->secretKey = $key;
    }

    public function initPayment(array $data): object
    {
        return $this->request('post', '/payments/initialize', $data);
    }

    public function verifyPayment(string $transactionId): object
    {
        return $this->request('get', "/payments/{$transactionId}/verify", []);
    }

    private function request(string $method, string $endpoint, array $data): object
    {
        $response = Http::asJson()
            ->acceptJson()
            ->timeout(self::TIMEOUT)
            ->withToken($this->secretKey, 'Bearer')
            ->baseUrl(self::BASE_URL)
            ->{$method}($endpoint, $data);

        $payload = json_decode($response->body(), false, 512, JSON_THROW_ON_ERROR);

        if ($response->successful()) {
            return $payload->data ?? $payload;
        }

        throw new \RuntimeException(
            $payload->message ?? 'Erreur API Moneroo (HTTP ' . $response->status() . ')'
        );
    }
}
