<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\Approval;
use Carbon\Carbon;

class CancelledOrderListExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct()
    {
        $this->startOfWeek = Carbon::now('+07:00')->startOfWeek();
        $this->endOfWeek = Carbon::now('+07:00')->endOfWeek();
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }
    
    public function view(): View
    {
        $exportDate = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $year = Carbon::now('+07:00');
        $since = '2020';
        $beginDate = Carbon::createFromFormat('Y-m-d', '1899-12-29');

        $startDate = $this->formatTanggal($this->startOfWeek, 'd-M-y');
        $endDate = $this->formatTanggal($this->endOfWeek, 'd-M-y');

        $cancelledOrders = Approval::query()
            ->select(
                'so.id AS id', 
                'so.tgl_so AS so_date',
                'approval.tanggal AS approval_date',
                'customer.nama AS customer_name',
                'so.total AS subtotal',
                'approval.status AS status',
                'approval.keterangan AS description',
            )
            ->join('so', 'so.id', 'approval.id_dokumen')
            ->leftJoin('customer', 'customer.id', 'so.id_customer')
            ->where('approval.tipe', 'Faktur')
            ->where('approval.status', 'BATAL')
            ->whereBetween('approval.tanggal', [$this->startOfWeek, $this->endOfWeek])
            ->orderBy('approval_date')
            ->orderBy('id')
            ->get();

        $data = [
            'exportDate' => $exportDate,
            'year' => $year,
            'since' => $since,
            'items' => $cancelledOrders,
            'beginDate' => $beginDate,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        
        return view('pages.patch.cancelled-order.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('List-Faktur');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
        
        $cancelledOrders = Approval::query()
            ->join('so', 'so.id', 'approval.id_dokumen')
            ->leftJoin('customer', 'customer.id', 'so.id_customer')
            ->where('approval.tipe', 'Faktur')
            ->where('approval.status', 'BATAL')
            ->whereBetween('approval.tanggal', [$this->startOfWeek, $this->endOfWeek])
            ->get();
        
        $range = 5 + $cancelledOrders->count();
        $rangeStr = strval($range);
        $rangeTab = 'H'.$rangeStr;

        $header = 'A5:H5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');
        $title = 'A1:H3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:H3')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $rangeTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A6:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

        $sheet->getStyle('B6:B'.$rangeStr)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_XLSX15);
        $sheet->getStyle('D6:D'.$rangeStr)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_XLSX15);

        $rangeTot = 'F6:F'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');
    } 
}
