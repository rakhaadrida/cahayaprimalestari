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

class KomisiFilterExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $month, String $kategori, String $tanggal, String $lastTanggal)
    {
        $this->month = $month;
        $this->kategori = $kategori;
        $this->tanggal = $tanggal;
        $this->lastTanggal = $lastTanggal;
    }
    
    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $bulNow = $waktu->month;
        $waktu = $waktu->format('d F Y, H:i:s');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        if($this->kategori == 'ALL')  {
            $stat[0] = 'EXTRANA';
            $stat[1] = 'PRIME';
        }
        else {
            $stat[0] = ($this->kategori == 'EXTRANA' ? 'EXTRANA' : '');
            $stat[1] = ($this->kategori == 'PRIME' ? 'PRIME' : '');
        }
        
        $monthNow = Carbon::parse($this->tanggal)->format('Y-m-20');
        $bulanNow = Carbon::parse($this->tanggal)->isoFormat('MMMM'); 
        $lastMonth = Carbon::parse($this->lastTanggal)->format('Y-m-21');
        $bulanLast = Carbon::parse($this->lastTanggal)->isoFormat('MMMM');
        
        if($this->kategori == 'ALL') {
            $items = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id', 'customer.id_sales')
                    ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                    ->where('kategori', 'NOT LIKE', $stat[0].'%')
                    ->where('kategori', 'NOT LIKE', $stat[1].'%')
                    ->where('id_sales', 'SLS12')
                    ->orderBy('tgl_so', 'desc')->orderBy('customer.nama', 'asc')->get();
        } else {
            $items = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                ->where('id_sales', 'SLS12')
                ->where('kategori', 'LIKE', $this->kategori.'%')
                ->orderBy('tgl_so', 'desc')->orderBy('customer.nama', 'asc')->get();
        }

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
        
        return view('pages.komisi.excelFilter', $data);
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
        $bulNow = $date->month;
        $monthNow = Carbon::parse($this->tanggal)->format('Y-m-20');
        $lastMonth = Carbon::parse($this->lastTanggal)->format('Y-m-21');

        if($this->kategori == 'ALL')  {
            $stat[0] = 'EXTRANA';
            $stat[1] = 'PRIME';
        }
        else {
            $stat[0] = ($this->kategori == 'EXTRANA' ? 'EXTRANA' : '');
            $stat[1] = ($this->kategori == 'PRIME' ? 'PRIME' : '');
        }
        
        if($this->kategori == 'ALL') {
            $items = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id', 'customer.id_sales')
                    ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                    ->where('kategori', 'NOT LIKE', $stat[0].'%')
                    ->where('kategori', 'NOT LIKE', $stat[1].'%')
                    ->where('id_sales', 'SLS12')
                    ->orderBy('customer.nama', 'asc')->get();
        } else {
            $items = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                ->where('id_sales', 'SLS12')
                ->where('kategori', 'LIKE', $this->kategori.'%')
                ->orderBy('customer.nama', 'asc')->get();
        }

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
