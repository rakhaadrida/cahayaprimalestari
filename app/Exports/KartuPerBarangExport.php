<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\StokBarang;
use App\Models\Barang;
use App\Models\DetilBM;
use App\Models\DetilSO;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class KartuPerBarangExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $kode, String $awal, String $akhir)
    {
        $this->kode = $kode;
        $this->awal = $awal;
        $this->akhir = $akhir;
    }
    
    public function view(): View
    {
        $barang = Barang::All();
        $tglAwal = $this->awal;
        $tglAkhir = $this->akhir;
        $tahun = Carbon::now();
        $sejak = '2020';

        $rowBM = DetilBM::with(['bm', 'barang'])
                    ->where('id_barang', $this->kode)
                    ->whereHas('bm', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir]);
                    })->get();
        $rowSO = DetilSO::with(['so', 'barang'])
                    ->where('id_barang', $this->kode)
                    ->whereHas('so', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tgl_so', [$this->awal, $this->akhir]);
                    })->get();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->where('id_barang', $this->kode)
                        ->groupBy('id_barang')->get();

        $i = 0;
        $stokAwal = 0;
        foreach($stok as $s) {
            $stokAwal = $s->total;
            foreach($rowBM as $bm) {
                $stokAwal -= $bm->qty;
            }

            foreach($rowSO as $so) {
                $stokAwal += $so->qty;
            }

            $i++;
        }

        $data = [
            'barang' => $barang,
            'rowBM' => $rowBM,
            'rowSO' => $rowSO,
            'awal' => $tglAwal,
            'akhir' => $tglAkhir,
            'stok' => $stok,
            'stokAwal' => $stokAwal,
            'tahun' => $tahun,
            'sejak' => $sejak
        ];
        
        return view('pages.laporan.excelKartu', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('KS-'.$this->kode);
        
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');
        $sheet->mergeCells('A5:M5');
        $sheet->mergeCells('A6:M6');

        $title = 'A1:M3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:M3')->getFont()->setSize(12);

        $infoBrg = 'A5:M6';
        $sheet->getStyle($infoBrg)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($infoBrg)->getAlignment()->setHorizontal('left');

        $header = 'A7:M8';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle($header)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');

        $tglAwal = $this->awal;
        $tglAkhir = $this->akhir;
        $rowBM = DetilBM::with(['bm', 'barang'])
                    ->where('id_barang', $this->kode)
                    ->whereHas('bm', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir]);
                    })->count();
        $rowSO = DetilSO::with(['so', 'barang'])
                    ->where('id_barang', $this->kode)
                    ->whereHas('so', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tgl_so', [$this->awal, $this->akhir]);
                    })->count();

        $range = 9 + $rowBM + $rowSO + 2;
        $rangeStr = strval($range);
        $rangeMinOne = strval($range-1);
        $rangeTab = 'M'.$rangeStr;

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];

        $rangeTable = 'A7:M'.$rangeStr;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $stokAwal = 'A9:E9';
        $total = 'A'.$rangeMinOne.':E'.$rangeMinOne;
        $stokAkhir = 'A'.$rangeStr.':E'.$rangeStr;
        $sheet->getStyle($stokAwal)->getFont()->setBold(true);
        $sheet->getStyle($stokAwal)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($total)->getFont()->setBold(true);
        $sheet->getStyle($total)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($stokAkhir)->getFont()->setBold(true);
        $sheet->getStyle($stokAkhir)->getAlignment()->setHorizontal('center');

        $awalRow = 'F9:M9';
        $totalRow = 'F'.$rangeMinOne.':M'.$rangeMinOne;
        $akhirRow = 'F'.$rangeStr.':M'.$rangeStr;
        $akhirFull = 'A'.$rangeStr.':M'.$rangeStr;

        $sheet->getStyle($awalRow)->getFont()->setBold(true);
        $sheet->getStyle($awalRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($totalRow)->getFont()->setBold(true);
        $sheet->getStyle($totalRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($akhirRow)->getFont()->setBold(true);
        $sheet->getStyle($akhirRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($akhirFull)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');

        $rangeIsiTable = 'A9:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

        for($i = 9; $i <= $range-1; $i+=2) {
            $rangeRow = 'A'.$i.':M'.$i;
            $sheet->getStyle($rangeRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('d6d7e2');
        }
    } 
}
