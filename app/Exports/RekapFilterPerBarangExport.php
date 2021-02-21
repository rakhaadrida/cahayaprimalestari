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
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\JenisBarang;
use App\Models\Subjenis;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapFilterPerBarangExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $kode, String $nama, String $awal)
    {
        $this->kode = $kode;
        $this->nama = $nama;
        $this->awal = $awal;
    }
    
    public function view(): View
    {
        $awal = $this->awal;
        $tglAwal = Carbon::parse($awal)->format('Y-m-d');
        $tglRekap = Carbon::parse($awal)->isoFormat('dddd, D MMMM Y');
        $kemarin = Carbon::yesterday()->toDateString();
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        // $waktu = $waktu->format('d F Y, H:i:s');
        $sub = Subjenis::where('id_kategori', $this->kode)->get();
        $gudang = Gudang::All();
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        $data = [
            'waktu' => $waktu,
            'sub' => $sub,
            'gudang' => $gudang,
            'nama' => $this->nama,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'awal' => $tglAwal,
            'kemarin' => $kemarin,
            'tglRekap' => $tglRekap
        ];
        
        return view('pages.laporan.rekapstok.excelFilter', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('RS-'.$this->nama);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
        
        $totSub = 0;
        $sub = Subjenis::where('id_kategori', $this->kode)->get();
        foreach($sub as $s) {
            $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
            $totSub += $barang->count();
        } 

        $alpha = range('A', 'Z');
        $gudang = Gudang::All();
        $angkaHuruf = $gudang->count() + 2;
        $huruf = $alpha[$angkaHuruf];
        
        $range = 5 + $totSub + $sub->count();
        $rangeStr = strval($range);
        $rangeTot = 'C'.$rangeStr;
        $rangeTab = $huruf.$rangeStr;

        $header = 'A5:'.$huruf.'5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        
        $sheet->mergeCells('A1:'.$huruf.'1');
        $sheet->mergeCells('A2:'.$huruf.'2');
        $sheet->mergeCells('A3:'.$huruf.'3');
        $title = 'A1:'.$huruf.'3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:'.$huruf.'3')->getFont()->setBold(false)->setSize(12);

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

        $rangeBarang = 'C5:'.$rangeTot;
        $sheet->getStyle($rangeBarang)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');
        
        $namaJenis = 'A6:'.$huruf.'6';
        $sheet->getStyle($namaJenis)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($namaJenis)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($namaJenis)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

        $no = 0;
        $rangeSub = 7;
        foreach($sub as $s) {
            $barang = Barang::where('id_sub', $s->id)->get();
            if($no != 0) 
                $rangeSub++;

            
            $rangeSub += $barang->count();
            $rangeJen = strval($rangeSub);
            $rangeBar = 'A'.$rangeSub.':'.$huruf.$rangeSub;

            if($no != $sub->count() - 1) {
                $sheet->getStyle($rangeBar)->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle($rangeBar)->getAlignment()->setHorizontal('center');
                $sheet->getStyle($rangeBar)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ffddb5');
            }
            $no++;
        }
    } 
}
