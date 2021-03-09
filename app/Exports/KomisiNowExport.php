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
use App\Models\AccReceivable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KomisiNowExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;
    
    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';
        
        $date = Carbon::now('+07:00');
        $monthNow = Carbon::parse($date)->format('Y-m-20');
        $bulanNow = Carbon::parse($date)->isoFormat('MMMM'); 
        $lastMonth = $date->subMonths(1)->format('Y-m-21');
        $bulanLast = Carbon::parse($date)->isoFormat('MMMM');
        
        $items = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                ->where('id_sales', 'SLS12')
                ->where('keterangan', 'BELUM LUNAS')
                ->orWhere(function($q) use($monthNow, $lastMonth) {
                    $q->where('keterangan', 'LUNAS')
                    ->whereBetween('ar.updated_at', [$lastMonth, $monthNow])
                    ->where('id_sales', 'SLS12');
                })->orderBy('customer.nama', 'asc')->get();

        $data = [
            'items' => $items,
            'monthNow' => $monthNow,
            'lastMonth' => $lastMonth,
            'bulanNow' => $bulanNow,
            'bulanLast' => $bulanLast,
            'waktu' => $waktu,
            'tahun' => $tahun,
            'sejak' => $sejak,
        ];
        
        return view('pages.komisi.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Komisi-Fadil');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
             
        $date = Carbon::now('+07:00');
        $monthNow = Carbon::parse($date)->format('Y-m-20');
        $lastMonth = $date->subMonths(1)->format('Y-m-21');
        
        $items = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                ->where('id_sales', 'SLS12')
                ->where('keterangan', 'BELUM LUNAS')
                ->orWhere(function($q) use($monthNow, $lastMonth) {
                    $q->where('keterangan', 'LUNAS')
                    ->whereBetween('ar.updated_at', [$lastMonth, $monthNow])
                    ->where('id_sales', 'SLS12');
                })->orderBy('customer.nama', 'desc')->get();

        $range = 6 + $items->count();
        $rangeStr = strval($range);
        $rangeTab = 'L'.$rangeStr;

        $header = 'A6:L6';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');
        
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');
        $sheet->mergeCells('A4:L4');
        $title = 'A1:L4';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:L4')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $rangeTot = 'H7:K'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

        $rangeTable = 'A6:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A7:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);
        
    } 
}
