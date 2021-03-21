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
use App\Models\Customer;
use App\Models\Sales;
use App\Models\DetilSO;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrimeFilterExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(Array $month, String $kode, String $mo)
    {
        $this->month = $month;
        $this->kode = $kode;
        $this->mo = $mo;
    }
    
    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $bulNow = $waktu->month;
        $waktu = $waktu->format('d F Y, H:i:s');
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $sejak = '2020';

        if($this->kode == 'KOSONG') {
            $customer = Customer::All();
            $sales = Sales::All();
        } else {
            $customer = Customer::where('id', $this->kode)->get();
            $sales = SalesOrder::select('id_sales as id')->where('id_customer', $this->kode)
                    ->groupBy('id_sales')->get();
        }

        $data = [
            'waktu' => $waktu,
            'tahun' => $tahun,
            'month' => $this->month,
            'cust' => $this->kode,
            'sejak' => $sejak,
            'date' => $date,
            'customer' => $customer,
            'sales' => $sales,
            'bulanNow' => $this->mo
        ];
        
        return view('pages.prime.excelFilter', $data);
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
        
        if($this->kode == 'KOSONG') {
            $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->select('id_customer', 'id_barang')
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                    ->whereIn(DB::raw('MONTH(tgl_so)'), $this->month)
                    ->groupBy('so.id_sales', 'id_customer', 'id_barang')->get();
            $sales = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->select('id_sales')
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                    ->whereIn(DB::raw('MONTH(tgl_so)'), $this->month)
                    ->groupBy('id_sales')->get();
        } else {
            $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->select('id_customer', 'id_barang')
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('id_customer', $this->kode)
                    ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                    ->whereIn(DB::raw('MONTH(tgl_so)'), $this->month)
                    ->groupBy('so.id_sales', 'id_customer', 'id_barang')->get();
            $sales = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->select('id_sales')
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('id_customer', $this->kode)
                    ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                    ->whereIn(DB::raw('MONTH(tgl_so)'), $this->month)
                    ->groupBy('id_sales')->get();
        }

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
            if($this->kode == 'KOSONG') {
                $barang = DetilSO::join('so', 'so.id', 'detilso.id_so')
                        ->join('barang', 'barang.id', 'detilso.id_barang')
                        ->select('id_customer', 'id_barang')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->where('id_sales', $s->id_sales)
                        ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                        ->whereIn(DB::raw('MONTH(tgl_so)'), $this->month)
                        ->groupBy('id_customer', 'id_barang')->get();
            } else {
                $barang = DetilSO::join('so', 'so.id', 'detilso.id_so')
                        ->join('barang', 'barang.id', 'detilso.id_barang')
                        ->select('id_customer', 'id_barang')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->where('id_customer', $this->kode)
                        ->where('id_sales', $s->id_sales)
                        ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                        ->whereIn(DB::raw('MONTH(tgl_so)'), $this->month)
                        ->groupBy('id_customer', 'id_barang')->get();
            }

            if($no != 0) 
                $rangeSub++;

            $rangeSub += $barang->count();
            $rangeJen = strval($rangeSub);
            $rangeBar = 'A'.$rangeSub.':F'.$rangeSub;

            $sheet->getStyle($rangeBar)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($rangeBar)->getAlignment()->setHorizontal('right');
            $sheet->getStyle($rangeBar)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');
            $no++;
        }

        $tot = $range;
        $totStr = strval($tot);
        $rangeGrandTot = 'A'.$rangeStr.':F'.$rangeStr;
        $sheet->getStyle($rangeGrandTot)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($rangeGrandTot)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($rangeGrandTot)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');
    }  
}
