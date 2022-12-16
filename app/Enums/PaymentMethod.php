<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Card = "card";
    case Klarna = "klarna";
    case BankTransfer = "bank transfer";
    case Check = "check";
    case Cash = "cash";
    case Other = "other";
}
