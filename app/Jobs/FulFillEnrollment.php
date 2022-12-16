<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Enrollment;
use App\Enums\FinancingType;
use App\Enums\PaymentMethod;
use Illuminate\Bus\Queueable;
use App\Enums\EnrollmentStatus;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FulFillEnrollment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $deleteWhenMissingModels = true;

    public function __construct(
        public Enrollment $enrollment,
        public ?PaymentMethod $paymentMethod = null,
        public ?User $paymentApprover = null
    ) {
    }

    public function handle()
    {
        if ($this->enrollment->financing_type === FinancingType::Manual) {
            $this->enrollment->payment_method = $this->paymentMethod;
            $this->enrollment->payment_approver_id = $this->paymentApprover?->id;
            $this->enrollment->paid_at = now();
        }

        $this->enrollment->status = EnrollmentStatus::Complete;
        $this->enrollment->completed_at = now();

        $this->enrollment->save();

        // TODO : send users & leads notifications
    }
}
