<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Pending = "pending";
    case Canceled = "canceled";
    case ContractSigned = "contract signed";
    case Complete = "complete";
}
