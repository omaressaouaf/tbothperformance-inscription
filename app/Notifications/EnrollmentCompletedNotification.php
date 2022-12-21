<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Notifications\Notification;
use App\Notifications\Traits\IsLocalNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EnrollmentCompletedNotification extends Notification implements ShouldQueue
{
    use IsLocalNotification, Queueable;

    public function __construct(public Enrollment $enrollment)
    {
    }

    public function via(): array
    {
        return ["database", "broadcast", "mail"];
    }

    public function toMail()
    {
        $presentation = static::presentation($this->data());

        return (new MailMessage)
            ->subject(__("New completed enrollment"))
            ->greeting(__("New completed enrollment"))
            ->line($presentation["message"])
            ->action(__("View enrollment"), $presentation["url"]);
    }

    public function data(): array
    {
        return [
            "lead_full_name" => sprintf(
                "%s %s",
                $this->enrollment->lead_data["first_name"],
                $this->enrollment->lead_data["last_name"]
            ),
            "enrollment_number" => $this->enrollment->number,
            "enrollment_id" => $this->enrollment->id,
        ];
    }

    public static function presentation(array $data): array
    {
        return [
            "url" => route("admin.enrollments.show", ["enrollment" => $data["enrollment_id"]]),
            "message" => __(
                "Lead :lead has just completed an enrollment :enrollment",
                ['lead' => $data["lead_full_name"], "enrollment" => $data["enrollment_number"]]
            ),
            "icon" => "BookmarkIcon",
            "icon_background" => "bg-theme-20"
        ];
    }
}
