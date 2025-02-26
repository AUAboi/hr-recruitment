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
            'Projects',
            'Programming Language',
            'Education History',
            'Matric Education',
            'Intermediate Education',
            'Bachelors Education',
            'Masters Education',
            'PhD. Education',
            'Summary',
        ];
    }

    /**
     * @param JobApplication $evaluation
     */
    public function map($evaluation): array
    {
        // $sheet->getCell('E20')->getHyperlink()->setUrl('http://www.google.com');


        $evaluation->data =  is_array($evaluation->data) ? $evaluation->data : json_decode($evaluation->data);


        $this->rowNumber++;

        $phone = isset($evaluation->data['phone_no']) ? (string) new PhoneNumber($evaluation->data['phone_no'], 'PK') : "";

        $name = isset($evaluation->data['name']) ? ucwords(strtolower($evaluation->data['name'])) : "";

        $last_name = isset($evaluation->data['last_name']) ? ucwords(strtolower($evaluation->data['last_name'])) : "";

        $skills = isset($evaluation->data['skills']) ? implode(', ', $evaluation->data['skills']) : null;

        $project = isset($evaluation->data['project']) ? implode(', ', $evaluation->data['project']) : null;

        $programming_language = isset($evaluation->data['programming_language']) ? implode(', ', $evaluation->data['programming_language']) : null;

        $education_history = isset($evaluation->data['education_history']) ? implode(', ', $evaluation->data['education_history']) : null;

        $matric_education = isset($evaluation->data['matric_education']) ? implode(', ', $evaluation->data['matric_education']) : null;
        $intermediate_education = isset($evaluation->data['intermediate_education']) ? implode(', ', $evaluation->data['intermediate_education']) : null;
        $bachelors_education = isset($evaluation->data['bachelors_education']) ? implode(', ', $evaluation->data['bachelors_education']) : null;
        $masters_education = isset($evaluation->data['masters_education']) ? implode(', ', $evaluation->data['masters_education']) : null;
        $phd_education = isset($evaluation->data['phd_education']) ? implode(', ', $evaluation->data['phd_education']) : null;

        return [
            $this->rowNumber,
            $name,
            $evaluation->data['email'] ?? null,
            $last_name,
            $phone,
            route('recruiter.evaluation.downloadPDF', $evaluation->id),
            $evaluation->data['address'] ?? null,
            $skills,
            $project,
            $programming_language,
            $education_history,
            $matric_education,
            $intermediate_education,
            $bachelors_education,
            $masters_education,
            $phd_education,
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
