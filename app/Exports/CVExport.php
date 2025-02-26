<?php

namespace App\Exports;

use App\Models\Evaluation;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\Rules\Phone;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

Sheet::macro('setURL', function (Sheet $sheet, string $cell, string $url) {
    $sheet->getCell($cell)->getHyperlink()->setUrl($url);
});
class CVExport implements FromCollection, WithMapping, ShouldAutoSize, WithHeadings, WithStyles, WithColumnFormatting, WithEvents
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
            'Last Name',
            'Phone No.',
            'CV URL',
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
     * @param Evaluation  $invoice
     */
    public function map($evaluation): array
    {
        // $sheet->getCell('E20')->getHyperlink()->setUrl('http://www.google.com');

        $this->rowNumber++;

        $phone = isset($evaluation->data['phone_no']) ? (string) new PhoneNumber($evaluation->data['phone_no'], 'PK') : "";

        $name = isset($evaluation->data['name']) ? ucwords(strtolower($evaluation->data['name'])) : "";

        $last_name = $evaluation->data['last_name'] ? ucwords(strtolower($evaluation->data['last_name'])) : "";
        return [
            $this->rowNumber,
            $name,
            $evaluation->data['email'] ?? null,
            $last_name,
            $phone,
            route('recruiter.evaluation.downloadPDF', $evaluation->id),
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                /** @var Worksheet $sheet */
                foreach ($event->sheet->getColumnIterator('F') as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        if (str_contains($cell->getValue(), '://')) {
                            $cell->setHyperlink(new Hyperlink($cell->getValue(), 'Read'));

                            // Upd: Link styling added
                            $event->sheet->getStyle($cell->getCoordinate())->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0000FF'],
                                    'underline' => 'single'
                                ]
                            ]);
                        }
                    }
                }
            },
        ];
    }
}
