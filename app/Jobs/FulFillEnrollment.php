<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Enrollment;
use App\Enums\PaymentMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EnrollmentCompletedNotification;

class FulFillEnrollment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $deleteWhenMissingModels = true;

    public function __construct(
        public Enrollment $enrollment,
        public ?PaymentMethod $paymentMethod = null,
        public Carbon|string|null $paidAt = null,
        public ?User $completedBy = null
    ) {
    }

    public function handle()
    {
        $this->enrollment->markAsComplete($this->paymentMethod, $this->paidAt, $this->completedBy);

        if (!$this->completedBy) {
            Notification::send(User::all(), new EnrollmentCompletedNotification($this->enrollment));
        }
    }
}
