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
use App\Models\DetilSO;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExtranaExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $bulan)
    {
        $this->bulan = $bulan;
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function view(): View
    {
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $tahun = Carbon::now('+07:00');
        // $tahun = '2021';
        $sejak = '2020';

        $bul = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        for($i = 0; $i < sizeof($bul); $i++) {
            if($this->bulan == $bul[$i]) {
                $month = $i+1;
                break;
            }
            else
                $month = '';
        }

        $awal = Carbon::createFromFormat('Y-m-d', '1899-12-30');

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->select('id_so', 'sales.nama as sales', 'customer.nama as cust', 'id_barang', 'harga')
                ->selectRaw('sum(qty) as qty, sum(diskonRp) as diskonRp')
                ->where('id_kategori', 'KAT03')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereYear('tgl_so', $tahun->year)->whereMonth('tgl_so', $month)
                ->groupBy('id_customer', 'id_barang', 'harga')->orderBy('so.id_sales')
                ->orderBy('customer.nama')->orderBy('id_so')->get();

        $data = [
            'waktu' => $waktu,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items,
            'bulan' => $this->bulan,
            'awal' => $awal
        ];

        return view('pages.laporan.extrana.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $tahun = Carbon::now('+07:00');
        // $tahun = '2021';
        $bul = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        for($i = 0; $i < sizeof($bul); $i++) {
            if($this->bulan == $bul[$i]) {
                $month = $i+1;
                break;
            }
            else
                $month = '';
        }

        $sheet->setTitle('BK-'.$this->bulan);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
        $sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(13);

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->select('id_so', 'sales.nama as sales', 'customer.nama as cust', 'id_barang', 'harga')
                ->selectRaw('sum(qty) as qty, sum(diskonRp) as diskonRp')
                ->where('id_kategori', 'KAT03')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereYear('tgl_so', $tahun->year)->whereMonth('tgl_so', $month)
                ->groupBy('id_customer', 'id_barang', 'harga')->orderBy('so.id_sales')
                ->orderBy('customer.nama')->get();

        $range = 5 + $items->count();
        $rangeStr = strval($range);
        $rangeTab = 'K'.$rangeStr;

        $header = 'A5:K5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->mergeCells('A3:K3');
        $title = 'A1:K3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:K3')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('E6:E'.$rangeStr)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_XLSX15);

        $rangeTot = 'G6:K'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

        $rangeTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A6:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

    }
}
