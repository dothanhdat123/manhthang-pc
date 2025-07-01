<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class NewsletterExport implements FromView, ShouldAutoSize, WithEvents, WithColumnWidths
{
    /**
     * Data from list
     */
    protected $data;

    protected $numRow;

    /**
     * NewsletterExport constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->numRow = count($data);
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view("admin.exports.newsletter", [
            'newsletters' => $this->data,
        ]);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $beforeExport) {
                $beforeExport->writer->getProperties()->setCreator('Anam Software');
            },

            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A:C')->getFont()->setName("Tahoma");
                $event->sheet->getDelegate()->getStyle('A:C')->getFont()->setSize(11);
                $event->sheet->getDelegate()->getStyle('A:C')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(false);
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $event->sheet->getDelegate()->getStyle('A1:C' . ($this->numRow + 1))
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 7,
        ];
    }
}
