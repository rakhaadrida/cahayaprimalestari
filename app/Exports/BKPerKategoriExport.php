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

class BKPerKategoriExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $id, String $nama, String $tglAwal, String $tglAkhir)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function view(): View
    {
        set_time_limit(300);

        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        $awal = $this->formatTanggal($this->tglAwal, 'd');
        $akhir = $this->formatTanggal($this->tglAkhir, 'd M y');
        if($this->tglAkhir != $this->tglAwal) {
            $tanggal = $awal.'-'.$akhir;
        } else {
            $tanggal = $akhir;
        }

        $awal = Carbon::createFromFormat('Y-m-d', '1899-12-30');

        $items = DetilSO::select('id_so', 'so.tgl_so', 'customer.nama AS namaCustomer', 'sales.nama AS namaSales', 'barang.nama AS namaBarang', 'gudang.nama AS namaGudang')
                    ->selectRaw('sum(qty) as qty')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->join('gudang', 'gudang.id', 'detilso.id_gudang')
                    ->join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id', 'so.id_sales')
                    ->whereBetween('tgl_so', [$this->tglAwal, $this->tglAkhir])
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])->where('qty', '!=', 0)
                    ->groupBy('id_customer', 'id_barang', 'id_gudang')
                    ->orderBy('tgl_so')->orderBy('id_so')->get();

        $data = [
            'waktu' => $waktu,
            'tglAwal' => $this->tglAwal,
            'tglAkhir' => $this->tglAkhir,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items,
            'tanggal' => $tanggal,
            'awal' => $awal,
            'id' => $this->id,
            'nama' => $this->nama
        ];

        if($this->id == '0')
            return view('pages.laporan.barangkeluar.excel', $data);
        else
            return view('pages.laporan.barangkeluar.excelKat', $data);
    }

    public function styles(Worksheet $sheet)
    {
        if($this->id == '0') {
            $awal = $this->formatTanggal($this->tglAwal, 'd');
            $akhir = $this->formatTanggal($this->tglAkhir, 'd M y');
            if($this->tglAkhir != $this->tglAwal) {
                $tanggal = $awal.'-'.$akhir;
            } else {
                $tanggal = $akhir;
            }

            $tglAwal = $this->tglAwal;
            $tglAkhir = $this->tglAkhir;

            $sheet->setTitle('BK-'.$tanggal);

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
            $drawing->setHeight(50);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
            $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(7);

            $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->select('id_so', 'id_barang', 'id_gudang')
                ->selectRaw('sum(qty) as qty')
                ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('qty', '!=', 0)
                ->groupBy('id_customer', 'id_barang', 'id_gudang')
                ->orderBy('nama')
                ->get();

            $range = 5 + $items->count();
            $rangeStr = strval($range);
            $rangeTab = 'I'.$rangeStr;

            $header = 'A5:I5';
            $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($header)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ffddb5');

            $sheet->mergeCells('A1:I1');
            $sheet->mergeCells('A2:I2');
            $sheet->mergeCells('A3:I3');
            $title = 'A1:I3';
            $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A3:I3')->getFont()->setBold(false)->setSize(12);

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];

            $sheet->getStyle('C6:C'.$rangeStr)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_XLSX15);

            $rangeTot = 'I6:I'.$rangeStr;
            $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

            $rangeTable = 'A5:'.$rangeTab;
            $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

            $rangeIsiTable = 'A6:'.$rangeTab;
            $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);
        } else {
            $sheet->setTitle('BK-'.$this->nama);

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
            $drawing->setHeight(50);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
            $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(8);

            $barang = DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                    ->join('so', 'so.id', 'detilso.id_so')
                    ->select('id_so as id', 'detilso.*')
                    ->selectRaw('sum(qty) as qty, sum(diskonRp) as diskonRp')
                    ->where('id_kategori', $this->id)
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                    ->where('id_customer', '!=', 'CUS1071')
                    ->whereBetween('tgl_so', [$this->tglAwal, $this->tglAkhir])
                    ->groupBy('id_so', 'id_barang')->get();

            $range = 5 + $barang->count();
            $rangeStr = strval($range);
            $rangeTot = 'C'.$rangeStr;
            $rangeTab = 'I'.$rangeStr;

            $header = 'A4:I4';
            $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($header)->getAlignment()->setHorizontal('center');

            $sheet->mergeCells('A1:I1');
            $sheet->mergeCells('A2:I2');
            $title = 'A1:I2';
            $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A2:I2')->getFont()->setBold(false)->setSize(12);

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

            $rangeHarga = 'D5:I'.$rangeStr;
            $sheet->getStyle($rangeHarga)->getNumberFormat()->setFormatCode('#,##0');

            $namaJenis = 'A4:I4';
            $sheet->getStyle($namaJenis)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($namaJenis)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($namaJenis)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ffddb5');

            $TH = 4; $rowTotal = 5;

            $TH += ($barang->count() + 4);
            $strTH = strval($TH);

            $TR = 'A'.$strTH.':I'.$strTH;

            $awal = $rowTotal;
            $strAwal = strval($awal);

            $rowTotal = $range;
            $strTotal = $rangeStr;
            $strTotOne = strval($rangeStr-1);
            $RT = 'A'.$rangeStr.':I'.$rangeStr;

            $sheet->getStyle($RT)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($RT)->getAlignment()->setHorizontal('right');
            $sheet->getStyle($RT)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00');

            $sheet->setCellValue('D'.$strTotal, '=SUM(D'.$strAwal.':D'.$strTotOne.')');
            $sheet->setCellValue('F'.$strTotal, '=SUM(F'.$strAwal.':F'.$strTotOne.')');
            $sheet->setCellValue('H'.$strTotal, '=SUM(H'.$strAwal.':H'.$strTotOne.')');
            $sheet->setCellValue('I'.$strTotal, '=SUM(I'.$strAwal.':I'.$strTotOne.')');
        }
    }
}
