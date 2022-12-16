<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Pending = "pending";
    case ContractSigned = "contract signed";
    case Complete = "complete";
    case Canceled = "canceled";
    case Failed = "failed";
}
