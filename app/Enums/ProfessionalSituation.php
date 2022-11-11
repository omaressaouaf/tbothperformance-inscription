<?php

namespace App\Enums;

enum ProfessionalSituation: string
{
    case Employee = "employee";
    case Freelancer = "freelancer";
    case AutoEntrepreneur = "auto entrepreneur";
    case JobSeeker = "job seeker";
    case Entrepreneur = "entrepreneur";
    case PublicAgent = "public agent";
    case Student = "student";
    case Retired = "retired";
}
