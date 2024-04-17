<?php

namespace App\Exports;

use App\Models\Evaluation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\Rules\Phone;

class CVExport implements FromCollection, WithMapping, ShouldAutoSize, WithHeadings, WithStyles, WithColumnFormatting
{
    private $rowNumber = 0;

    public function collection()
    {
        return Evaluation::all();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 20]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => 0,
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Father Name',
            'Phone No.',
            'Address',
            'Skills',
            // 'Projects',
            'Programming Language',
            'Matric Education',
            'Intermediate Education',
            'Bachelors Education',
            'Masters Education',
            'PhD. Education',
            'Summary',
        ];
    }

    /**
     * @param Evaluation $invoice
     */
    public function map($evaluation): array
    {
        $this->rowNumber++;

        $phone = 0;
        if (isset($evaluation->data['phone_no'])) {
            $phone = (string) new PhoneNumber($evaluation->data['phone_no'], 'PK');
        }

        return [
            $this->rowNumber,
            $evaluation->data['name'] ?? null,
            $evaluation->data['email'] ?? null,
            $evaluation->data['father_name'] ?? null,
            $phone,
            $evaluation->data['address'] ?? null,
            implode(', ', $evaluation->data['skills']) ?? null,
            // implode(', ', $evaluation->data['projects']) ?? null,
            implode(', ', $evaluation->data['programming_language']) ?? null,
            implode(', ', $evaluation->data['matric_education'])  ?? null,
            implode(', ', $evaluation->data['intermediate_education']) ?? null,
            implode(', ', $evaluation->data['bachelors_education']) ?? null,
            implode(', ', $evaluation->data['masters_education']) ?? null,
            implode(', ', $evaluation->data['phd_education']) ?? null,
            $evaluation->data['summary'] ?? null,
        ];
    }
}
