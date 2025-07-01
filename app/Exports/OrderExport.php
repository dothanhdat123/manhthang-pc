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

class OrderExport implements FromView, ShouldAutoSize, WithEvents, WithColumnWidths
{
    /**
     * Data
     */
    protected $data;

    /**
     * @var int Num row
     */
    protected $numRow;

    /**
     * ContactExport constructor.
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
        return view("admin.exports.orders.index", [
            'results' => $this->data,
        ]);
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 40,
            'C' => 15,
            'D' => 40,
            'E' => 15,
            'F' => 15,
        ];
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
                $event->sheet->getDelegate()->getStyle('A:J')->getFont()->setName("Tahoma");
                $event->sheet->getDelegate()->getStyle('A:J')->getFont()->setSize(11);
                $event->sheet->getDelegate()->getStyle('A:J')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(false);
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $event->sheet->getDelegate()->getStyle('A1:J' . ($this->numRow + 1))
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }
        ];
    }
}
