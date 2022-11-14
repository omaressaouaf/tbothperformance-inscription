<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Pending = "pending";
    case Canceled = "canceled";
    case Complete = "complete";
}
