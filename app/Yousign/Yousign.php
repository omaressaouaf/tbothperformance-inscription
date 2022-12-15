<?php

namespace App\Yousign;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;

class Yousign
{
    const BASE_URL = [
        "sandbox" => "https://api-sandbox.yousign.app/v3/",
        "production" => "https://api.yousign.app/v3/",
    ];

    private string $apiKey;

    private string $apiEnv;

    public function __construct()
    {
        $this->setApiKey(env("YOUSIGN_KEY"));
        $this->setApiEnv(env("YOUSIGN_ENV"));
    }

    public function getBaseUrl(): string
    {
        return self::BASE_URL[$this->apiEnv];
    }

    protected function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    protected function setApiEnv(string $apiEnv): void
    {
        if (!in_array($apiEnv, ["sandbox", "production"])) {
            throw new \InvalidArgumentException("Yousign env must be either sandbox or production");
        }

        $this->apiEnv = $apiEnv;
    }

    public function getApiEnv(): string
    {
        return $this->apiEnv;
    }

    protected function makeRequest(
        string $method,
        string $path,
        array $params  = [],
        ?callable $callable = null,
        bool $json = true,
    ): mixed {
        $client = Http::withOptions(["debug" => false])
            ->withToken($this->apiKey)
            ->retry(3, 100, fn ($exception) => $exception instanceof ConnectionException);

        if ($callable) {
            $client = $callable($client);
        }

        if ($json) {
            $client = $client->acceptJson();
        }

        $response = $client->{$method}($this->getBaseUrl() . $path, $params)->throw();

        return $json ? $response->json() : $response;
    }

    public function uploadDocument(string $nature = "attachment", string $path): mixed
    {
        return $this->makeRequest(
            "post",
            "documents",
            ["nature" => $nature, "parse_anchors" => "true"],
            fn ($client) => $client->attach('file', file_get_contents($path), basename($path))
        );
    }

    public function initiateSignatureRequest(array $data): mixed
    {
        return $this->makeRequest("post", "signature_requests", $data);
    }

    public function activateSignatureRequest(mixed $signatureRequestId): mixed
    {
        return $this->makeRequest("post", "signature_requests/{$signatureRequestId}/activate");
    }

    public function reactivateSignatureRequest(mixed $signatureRequestId, array $data): mixed
    {
        return $this->makeRequest("post", "/signature_requests/{$signatureRequestId}/reactivate", $data);
    }

    public function getSignatureRequest(mixed $signatureRequestId): mixed
    {
        return $this->makeRequest("get", "signature_requests/{$signatureRequestId}");
    }

    public function downloadSignatureRequestFile(mixed $signatureRequestId, array $data): mixed
    {
        return $this->makeRequest(
            "get",
            "signature_requests/{$signatureRequestId}/documents/download",
            $data,
            fn ($client) => $client->accept("application/zip, application/pdf"),
            false
        );
    }
}
