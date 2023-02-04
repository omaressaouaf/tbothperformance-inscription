<?php

namespace App\Calendly;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class Calendly
{
    const BASE_URL = "https://api.calendly.com/";

    private string $apiKey;

    public function __construct()
    {
        $this->setApiKey(config("services.calendly.key"));
    }

    public function getBaseUrl(): string
    {
        return self::BASE_URL;
    }

    protected function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
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

        $response = $client->{$method}($this->getBaseUrl() . $path, [
            ...$params,
            "organization" => config("services.calendly.organization_url")
        ])
            ->throw();

        return $json ? $response->json() : $response;
    }

    public function me(): mixed
    {
        return $this->makeRequest("get", "users/me");
    }

    public function getUser(string $userId): mixed
    {
        return $this->makeRequest("get", "users/{$userId}");
    }

    public function webhookIsValid(Request $request): bool
    {
        return (new WebhookValidator($request))->execute();
    }

    public function createWebhook(array $data): mixed
    {
        return $this->makeRequest("post", "webhook_subscriptions", $data);
    }

    public function getWebhooks(array $params): mixed
    {
        return $this->makeRequest("get", "webhook_subscriptions", $params);
    }

    public function getWebhook(string $webhookId): mixed
    {
        return $this->makeRequest("get", "webhook_subscriptions/{$webhookId}");
    }

    public function deleteWebhook(string $webhookId): mixed
    {
        return $this->makeRequest("delete", "webhook_subscriptions/{$webhookId}");
    }

    public function getEvents(): mixed
    {
        return $this->makeRequest("get", "scheduled_events");
    }

    public function getEvent(string $eventId): mixed
    {
        return $this->makeRequest("get", "scheduled_events/{$eventId}");
    }

    public function cancelEvent(string $eventId, array $data = []): mixed
    {
        return $this->makeRequest("post", "scheduled_events/{$eventId}/cancellation", $data);
    }

    public function getEventInvitees(string $eventId): mixed
    {
        return $this->makeRequest("get", "scheduled_events/{$eventId}/invitees");
    }

    public function extractId(string $seperator, string $url): string
    {
        return explode($seperator . "/", $url)[1];
    }
}
