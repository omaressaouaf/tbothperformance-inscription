<?php

namespace App\Exports;

use App\Models\Lead;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LeadsExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection()
    {
        return Lead::withCount(["enrollments"])->get();
    }

    public function map($lead): array
    {
        return [
            $lead->full_name,
            $lead->phone,
            $lead->email,
            __($lead->years_worked_in_france?->value),
            __($lead->professional_situation?->value),
            Date::dateTimeFromTimestamp($lead->created_at),
            $lead->enrollments_count,
        ];
    }

    public function headings(): array
    {
        return [
            __("Full name"),
            __("Phone"),
            __("Email"),
            __("Years worked in france"),
            __("Professional situation"),
            __("Joined at"),
            __("Enrollments"),
        ];
    }
}
