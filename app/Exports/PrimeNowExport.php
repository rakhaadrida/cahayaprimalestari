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
use App\Models\Sales;
use App\Models\DetilSO;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrimeNowExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;
    
    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->isoFormat('DD MMMM YYYY, HH:mm:ss');
        $tahun = Carbon::now('+07:00');
        $month[0] = $tahun->month;
        $sejak = '2020';
        
        $date = Carbon::now('+07:00');
        $monthNow = Carbon::parse($date)->format('Y-m-20');
        $bulanNow = Carbon::parse($date)->isoFormat('MMMM');
        $sales = Sales::All(); 

        $data = [
            'monthNow' => $monthNow,
            'bulanNow' => $bulanNow,
            'waktu' => $waktu,
            'tahun' => $tahun,
            'month' => $month,
            'sejak' => $sejak,
            'date' => $date,
            'sales' => $sales,
            'cust' => 'KOSONG'
        ];
        
        return view('pages.prime.excel', $data);
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
        $tahun = $date->year;
        $month = $date->month;
        
        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->select('id_customer', 'id_barang')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                ->whereMonth('tgl_so', $month)
                ->groupBy('so.id_sales', 'id_customer', 'id_barang')->get();
        $sales = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->select('id_sales')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                ->whereMonth('tgl_so', $month)
                ->groupBy('id_sales')->get();

        $range = 6 + $items->count() + $sales->count();
        $rangeStr = strval($range);
        $rangeTab = 'F'.$rangeStr;

        $header = 'A5:F5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');
        
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $title = 'A1:F3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:F3')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $rangeTot = 'F7:F'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

        $rangeTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A7:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

        $no = 0;
        $rangeSub = 6;
        foreach($sales as $s) {
            $barang = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->select('id_barang')
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('id_kategori', 'KAT08')
                    ->where('so.id_sales', $s->id_sales)->whereYear('tgl_so', $tahun)
                    ->whereMonth('tgl_so', $month)
                    ->groupBy('id_customer', 'id_barang')->get();
            if($no != 0) 
                $rangeSub++;

            $rangeSub += $barang->count();
            $rangeJen = strval($rangeSub);
            $rangeBar = 'A'.$rangeJen.':F'.$rangeJen;

            $sheet->getStyle($rangeBar)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($rangeBar)->getAlignment()->setHorizontal('right');
            $sheet->getStyle($rangeBar)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');
            $no++;
        }

        $rangeGrandTot = 'A'.$rangeStr.':F'.$rangeStr;
        $sheet->getStyle($rangeGrandTot)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($rangeGrandTot)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($rangeGrandTot)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');
    } 
}
