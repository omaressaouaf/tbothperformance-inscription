<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Traits\IsLocalNotification;
use Illuminate\Notifications\Messages\MailMessage;

class UserAssignedToEnrollmentNotification extends Notification implements ShouldQueue
{
    use IsLocalNotification, Queueable;

    public function __construct(public User $user, public Enrollment $enrollment)
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
            ->subject(__("New enrollment to process"))
            ->line($presentation["message"])
            ->action(__("View :resource", ["resource" => __("Enrollment")]), $presentation["url"]);
    }

    public function data(): array
    {
        return [
            "user_name" => $this->user->name,
            "enrollment_id" => $this->enrollment->id,
            "enrollment_number" => $this->enrollment->number
        ];
    }

    public static function presentation(array $data): array
    {
        return [
            "url" => route("admin.enrollments.show", ["enrollment" => $data["enrollment_id"]]),
            "message" => __(
                ":user has assigned you to process enrollment :enrollment",
                ['user' => $data["user_name"], "enrollment" => $data["enrollment_number"]]
            ),
            "icon" => "BookmarkIcon",
            "icon_background" => "bg-primary-11"
        ];
    }
}
