<?php

namespace App\Enums;

enum YearsWorkedInFrance: string
{
    case LessThan1Year = "less than 1 year";
    case Between1And2Years = "between 1 and 2 years";
    case MoreThan3Years = "more than 3 years";
    case Never = "never";
}
