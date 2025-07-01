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

class OrderDetailExport implements FromView, ShouldAutoSize, WithEvents, WithColumnWidths
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
        $this->numRow = count($data['order_details']);
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view("admin.exports.orders.detail", [
            'result' => $this->data,
        ]);
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'B' => 25,
            'C' => 25,
            'D' => 25,
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
                $event->sheet->getDelegate()->getStyle('A:D')->getFont()->setName("Tahoma");
                $event->sheet->getDelegate()->getStyle('A:D')->getFont()->setSize(11);
                $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(false);
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            }
        ];
    }
}
