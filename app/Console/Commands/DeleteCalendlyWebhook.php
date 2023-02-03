<?php

namespace App\Console\Commands;

use App\Calendly\Calendly;
use Illuminate\Console\Command;

class DeleteCalendlyWebhook extends Command
{
    protected $signature = 'calendly-webhooks:delete {id?} {--all}';

    protected $description = 'Delete calendly webhook';

    public function handle(Calendly $calendly)
    {
        $id = $this->argument("id");
        $all = $this->option("all");

        if ($id) {
            $calendly->deleteWebhook($id);

            $this->info("Webhook {$id} was deleted");
        }

        if ($all) {
            $webhooks = $calendly->getWebhooks(["scope" => "organization"])["collection"];

            foreach ($webhooks as $webhook) {
                $id = $calendly->extractId("webhook_subscriptions", $webhook["uri"]);

                $calendly->deleteWebhook($id);

                $this->info("Webhook {$id} was deleted");
            }
        }
    }
}
