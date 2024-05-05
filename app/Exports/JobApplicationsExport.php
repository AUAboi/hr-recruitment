<?php

namespace App\Exports;

use App\Models\Evaluation;
use App\Models\JobListing;
use Maatwebsite\Excel\Sheet;
use App\Models\JobApplication;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Propaganistas\LaravelPhone\PhoneNumber;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

Sheet::macro('setURL', function (Sheet $sheet, string $cell, string $url) {
    $sheet->getCell($cell)->getHyperlink()->setUrl($url);
});
class JobApplicationsExport implements FromCollection, WithMapping, ShouldAutoSize, WithHeadings, WithStyles, WithColumnFormatting, WithEvents
{
    private $rowNumber = 0;
    private $job_application;

    public function __construct(JobListing $job_listing)
    {
        $this->job_application = $job_listing->jobApplications;
    }

    public function collection()
    {
        return $this->job_application;
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
     * @param Evaluation $invoice
     */
    public function map($evaluation): array
    {
        // $sheet->getCell('E20')->getHyperlink()->setUrl('http://www.google.com');
        $this->rowNumber++;

        $phone = isset($evaluation->data['phone_no']) ? (string) new PhoneNumber($evaluation->data['phone_no'], 'PK') : "";

        $name = isset($evaluation->data['name']) ? ucwords(strtolower($evaluation->data['name'])) : "";

        $father_name = $evaluation->data['father_name'] ? ucwords(strtolower($evaluation->data['father_name'])) : "";
        return [
            $this->rowNumber,
            $name,
            $evaluation->data['email'] ?? null,
            $father_name,
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
