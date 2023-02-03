<?php

namespace App\Console\Commands;

use App\Calendly\Calendly;
use Illuminate\Console\Command;

class CreateCalendlyWebhook extends Command
{
    protected $signature = 'calendly-webhooks:create {--url=}';

    protected $description = 'Create calendly webhook';

    public function handle(Calendly $calendly)
    {
        $url = $this->option("url") ?? route("calendly.webhook");

        $calendly->createWebhook([
            "url" => $url,
            "events" => ["invitee.created", "invitee.canceled"],
            "scope" => "organization",
            "signing_key" => config("services.calendly.webhook_signing_key")
        ]);

        $this->info("Webhook created for url : " . $url);
    }
}
