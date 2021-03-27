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
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BMAllExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $tglAwal, String $tglAkhir,String $awal, String $akhir)
    {
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
        $this->awal = $awal;
        $this->akhir = $akhir;
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        $tglAwal = $this->tglAwal;
        $tglAkhir = $this->tglAkhir;

        $tglAwal = $this->formatTanggal($tglAwal, 'd-M-y');
        $tglAkhir = $this->formatTanggal($tglAkhir, 'd-M-y');

        $items = BarangMasuk::join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                ->select('barangmasuk.id as id', 'barangmasuk.*')->whereBetween('tanggal', [$this->awal, $this->akhir])
                ->orderBy('id')->get();

        $data = [
            'waktu' => $waktu,
            'awal' => $tglAwal,
            'akhir' => $tglAkhir,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items
        ];
        
        return view('pages.pembelian.bmharian.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('BMH-'.$this->awal);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
                
        // $so = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
        //         ->whereBetween('tgl_so', [$this->awal, $this->akhir])->get();
        $detil = DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                ->whereBetween('tanggal', [$this->awal, $this->akhir])->get();

        $range = 5 + $detil->count();
        $rangeStr = strval($range);
        $rangeTab = 'G'.$rangeStr;

        $header = 'A5:G5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');
        
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $title = 'A1:G3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:G3')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $rangeTot = 'G6:G'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

        $rangeTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A6:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);
        
    } 
}
