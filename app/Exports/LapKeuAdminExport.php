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

class LapKeuAdminExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $tahun, String $month)
    {
        $this->tahun = $tahun;
        $this->month = $month;
    }

    public function view(): View
    {
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

        $kat = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                ->whereYear('so.tgl_so', $this->tahun)->whereMonth('so.tgl_so', $this->month)
                ->groupBy('id_kategori')->orderBy('id_kategori')->get();

        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';
        $tanggal = $this->tahun.'-'.$this->month.'-01';
        $bul = Carbon::parse($tanggal)->isoFormat('MMMM');

        $data = [
            'waktu' => $waktu,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'bul' => $bul,
            'items' => $items,
            'retur' => $retur,
            'jenis' => $jenis,
            'sales' => $sales,
            'diskon' => $diskon,
            'kat' => $kat,
            'tah' => $this->tahun,
            'bulan' => $this->month
        ];

        return view('pages.keuangan.excelSum', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Rekap-Penjualan');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(15);
        $sheet->getRowDimension('4')->setRowHeight(30);

        $alpha = range('A', 'Z');
        $sales = Sales::All();
        $jenis = JenisBarang::All();
        $kat = DetilSO::join('so', 'so.id', 'detilso.id_so')
            ->join('barang', 'barang.id', 'detilso.id_barang')
            ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
            ->whereYear('so.tgl_so', $this->tahun)->whereMonth('so.tgl_so', $this->month)
            ->groupBy('id_kategori')->orderBy('id_kategori')->get();

        $lastAlpha = 2 + $jenis->count();
        $range = 4 + ($sales->count() * 2) + 2;
        $rangeStr = strval($range);
        $rangeTot = 'C'.$rangeStr;
        $rangeTab = $alpha[$lastAlpha].$rangeStr;

        $header = 'A4:'.$alpha[$lastAlpha].'4';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A1:'.$alpha[$lastAlpha].'1');
        $sheet->mergeCells('A2:'.$alpha[$lastAlpha].'2');
        $title = 'A1:'.$alpha[$lastAlpha].'2';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:'.$alpha[$lastAlpha].'2')->getFont()->setBold(false)->setSize(12);

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

        $rangeHarga = 'B5:'.$alpha[$lastAlpha].$rangeStr;
        $sheet->getStyle($rangeHarga)->getNumberFormat()->setFormatCode('#,##0');

        $rangeHarga = $alpha[$lastAlpha-1].'5:'.$alpha[$lastAlpha-1].$rangeStr;
        $sheet->getStyle($rangeHarga)->getNumberFormat()->setFormatCode('(#,##0)');

        $sheet->getStyle('A4:'.$alpha[$lastAlpha].$rangeStr)->getAlignment()->setVertical('center');

        $end = $range - 1;
        $rangeEnd = strval($end);
        $rangeTotal = $range - 2;
        $rangeStrTot = strval($rangeTotal);
        for($i = 1; $i <= ($jenis->count() + 2); $i++) {
            $sheet->setCellValue($alpha[$i].$rangeEnd, '=SUM('.$alpha[$i].'5:'.$alpha[$i].$rangeStrTot.')');
        }

        for($i = 5; $i <= ($sales->count() * 2 + 3); $i+=2) {
            $sheet->setCellValue($alpha[$lastAlpha].$i, '=SUM(B'.$i.':'.$alpha[$lastAlpha-2].$i.')-'.$alpha[$lastAlpha-1].$i);
        }

        $summary = 'A'.$rangeEnd.':'.$alpha[$lastAlpha].$rangeEnd;
        $sheet->getStyle($summary)->getFont()->setBold(true)->setSize(12);
        $GT = $alpha[$lastAlpha].'5:'.$alpha[$lastAlpha].$rangeStrTot;
        $sheet->getStyle($GT)->getFont()->setBold(true)->setSize(12);
    }
}
