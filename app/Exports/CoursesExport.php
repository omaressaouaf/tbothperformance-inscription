<?php

namespace App\Exports;

use App\Models\Course;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CoursesExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection()
    {
        return Course::with(["category", "plans"])->get();
    }

    public function map($course): array
    {
        return [
            $course->title,
            $course->certificate,
            $course->eligible_for_cpf ? __("Yes") : __("No"),
            $course->category?->name,
            $this->formatPlansForExport($course->plans),
            Date::dateTimeFromTimestamp($course->created_at),
        ];
    }

    public function headings(): array
    {
        return [
            __("Title"),
            __("Certificate"),
            __("Eligible for CPF"),
            __("Category"),
            __("Plans"),
            __("Creation date"),
        ];
    }

    private function formatPlansForExport(mixed $plans): string
    {
        return $plans->reduce(function ($carry, $plan) {
            $price = format_money($plan->price);

            return "{$plan->name} ({$price}) - $carry";
        }, "");
    }
}
