<?php

namespace App\Models;

use App\Calendly\Calendly;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        "start_at" => "datetime:Y-m-d H:i:s",
        "end_at" => "datetime:Y-m-d H:i:s",
        "invitee" => "array",
        "location" => "array"
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, "responsible_user_id");
    }

    public static function createFromCalendlyEvent(string $eventUrl): static
    {
        [$event, $eventInvitee, $eventUser] = static::getCalendlyEventResources($eventUrl);

        return Meeting::create([
            "event_id" => $event["id"],
            "event_name" => $event["name"],
            "start_at" => parse_date($event["start_time"])->setTimezone(config("app.timezone")),
            "end_at" => parse_date($event["end_time"])->setTimezone(config("app.timezone")),
            "location" => $event["location"],
            "invitee" => [
                "name" => $eventInvitee["name"],
                "email" => $eventInvitee["email"],
                "phone" => $event["location"]["type"] === "outbound_call"
                    ? $event["location"]["location"]
                    : (isset($eventInvitee["questions_and_answers"])
                        ? $eventInvitee["questions_and_answers"][0]["answer"] ?? null
                        : null),
                "timezone" => $eventInvitee["timezone"]
            ],
            "lead_id" => auth_user("lead")?->id,
            "responsible_user_id" => User::firstWhere("calendly_slug", $eventUser["slug"])?->id
        ]);
    }

    private static function getCalendlyEventResources(string $eventUrl): array
    {
        $calendly = app(Calendly::class);

        $eventId = $calendly->extractId("scheduled_events", $eventUrl);

        $event = $calendly->getEvent($eventId)["resource"];

        $event["id"] = $eventId;

        $eventInvitee = $calendly->getEventInvitees($event["id"])["collection"][0];

        $eventUserId = $calendly->extractId('users', $event["event_memberships"][0]["user"]);

        $eventUser = $calendly->getUser($eventUserId)["resource"];

        $eventUserSlug = $calendly->extractId("calendly.com", $eventUser["scheduling_url"]);

        $eventUser["slug"] = $eventUserSlug;

        return [$event, $eventInvitee, $eventUser];
    }
}
