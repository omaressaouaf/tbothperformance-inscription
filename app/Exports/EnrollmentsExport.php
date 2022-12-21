<?php

namespace App\Exports;

use App\Models\Enrollment;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EnrollmentsExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection()
    {
        return Enrollment::with(["lead", "course", "plan"])->when(
            request("leadId"),
            fn ($query) => $query->where("lead_id", request("leadId"))
        )->get();
    }

    public function map($enrollment): array
    {
        return [
            $enrollment->number,
            sprintf(
                "%s %s - %s - %s - %s - %s",
                $enrollment->lead_data["first_name"],
                $enrollment->lead_data["last_name"],
                $enrollment->lead_data["phone"],
                $enrollment->lead_data["email"],
                __($enrollment->lead_data["years_worked_in_france"]),
                __($enrollment->lead_data["professional_situation"]),
            ),
            __($enrollment->financing_type?->value),
            $enrollment->cpf_amount,
            $enrollment->cpf_dossier_number,
            __($enrollment->payment_method?->value),
            $enrollment->paid_at ? Date::dateTimeFromTimestamp($enrollment->paid_at) : "",
            $enrollment->course?->title,
            sprintf(
                "%s %s",
                $enrollment->plan ? $enrollment->plan->name : "",
                $enrollment->plan ? format_money($enrollment->plan?->price) : "",
            ),
            $enrollment->course_start_date
                ? Date::dateTimeFromTimestamp($enrollment->course_start_date)
                : "",
            __($enrollment->status?->value),
            Date::dateTimeFromTimestamp($enrollment->created_at),
            $enrollment->completed_at ? Date::dateTimeFromTimestamp($enrollment->completed_at) : "",
        ];
    }

    public function headings(): array
    {
        return [
            __("Number"),
            __("Lead"),
            __("Course"),
            __("Plan"),
            __("Course start date"),
            __("Financing"),
            __("CPF amount"),
            __("Dossier number"),
            __("Payment method"),
            __("Paid at"),
            __("Status"),
            __("Started at"),
            __("Finished at"),
        ];
    }
}
