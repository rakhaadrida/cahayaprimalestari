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

class BarangKeluarExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $tglAwal, String $tglAkhir)
    {
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function view(): View
    {
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

        $tglAwal = $this->tglAwal;
        $tglAkhir = $this->tglAkhir;

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->select('id_barang', 'id_gudang')->selectRaw('sum(qty) as qty')
                    ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])->groupBy('id_barang', 'id_gudang')->get();

        $data = [
            'waktu' => $waktu,
            'tglAwal' => $tglAwal,
            'tglAkhir' => $tglAkhir,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items,
            'tanggal' => $tanggal
        ];
        
        return view('pages.laporan.barangkeluar.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
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
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
                
        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->select('id_barang', 'id_gudang')->selectRaw('sum(qty) as qty')
                    ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])->groupBy('id_barang', 'id_gudang')->get();

        $range = 5 + $items->count();
        $rangeStr = strval($range);
        $rangeTab = 'E'.$rangeStr;

        $header = 'A5:E5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');
        
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $title = 'A1:E3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:E3')->getFont()->setBold(false)->setSize(12);

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
        
    } 
}
