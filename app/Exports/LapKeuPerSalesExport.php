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
use App\Models\SalesOrder;
use App\Models\JenisBarang;
use App\Models\DetilSO;
use App\Models\DetilRAR;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LapKeuPerSalesExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $id, String $tahun, String $month)
    {
        $this->id = $id;
        $this->tahun = $tahun;
        $this->month = $month;
    }
    
    public function view(): View
    {
        if($this->id == 0) {
            $jenis = JenisBarang::All();
            $sales = Sales::All();
            $diskon = SalesOrder::selectRaw('sum(diskon) as diskon')->whereYear('tgl_so', $this->tahun)
                ->whereMonth('tgl_so', $this->month)->get();

            $items = DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                    ->join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id', 'so.id_sales')
                    ->select('so.id_sales', 'sales.nama', 'barang.id_kategori', DB::raw('sum(harga * qty - diskonRp) as total'))
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                    ->whereYear('so.tgl_so', $this->tahun)
                    ->whereMonth('so.tgl_so', $this->month)
                    ->groupBy('so.id_sales', 'barang.id_kategori')
                    ->get();

            $retur = DetilRAR::join('barang', 'barang.id', 'detilrar.id_barang')
                    ->join('ar_retur', 'ar_retur.id', 'detilrar.id_retur')
                    ->join('ar', 'ar.id', 'ar_retur.id_ar')->join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id' , 'customer.id_sales')
                    ->select('so.id_sales', 'barang.id_kategori', DB::raw('sum((qty * harga) - diskonRp) as total'))
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT', 'RETUR']) 
                    ->whereYear('so.tgl_so', $this->tahun)
                    ->whereMonth('so.tgl_so', $this->month)
                    ->groupBy('so.id_sales', 'barang.id_kategori')
                    ->get();

            $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
            $tahun = Carbon::now('+07:00');
            $sejak = '2020';

            $data = [
                'waktu' => $waktu,
                'tahun' => $tahun,
                'sejak' => $sejak,
                'items' => $items,
                'retur' => $retur,
                'jenis' => $jenis,
                'sales' => $sales,
                'diskon' => $diskon
            ];
            
            return view('pages.keuangan.excel', $data);
        }
    }

    public function styles(Worksheet $sheet)
    {
        if($this->id == 0) {
            $sheet->setTitle('Lap-Keu');

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
            $drawing->setHeight(50);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
            $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5); 
            
            $sales = Sales::All();

            $range = 4 + ($sales->count() * 4) + 11;
            $rangeStr = strval($range);
            $rangeTot = 'C'.$rangeStr;
            $rangeTab = 'P'.$rangeStr;

            $header = 'A4:P4';
            $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
            
            $sheet->mergeCells('A1:P1');
            $sheet->mergeCells('A2:P2');
            $title = 'A1:P2';
            $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A2:P2')->getFont()->setBold(false)->setSize(12);

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];

            $rangeTable = 'A4:'.$rangeTab;
            $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

            $rangeIsiTable = 'A5:'.$rangeTab;
            $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

            $rangeHarga = 'D5:P'.$rangeStr;
            $sheet->getStyle($rangeHarga)->getNumberFormat()->setFormatCode('#,##0');
            
            $namaJenis = 'A4:P4';
            $sheet->getStyle($namaJenis)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($namaJenis)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($namaJenis)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ffddb5');

            $total = $range - 10;
            $rangeTotal = strval($total);
            $sheet->getStyle('A'.$rangeTotal.':P'.$rangeStr)->getAlignment()->setHorizontal('right');
            $sheet->getStyle('A'.$rangeTotal.':P'.$rangeStr)->getFont()->setBold(true)->setSize(12);;    
        }
    } 
}
