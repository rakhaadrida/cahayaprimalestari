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

class RekapPricePerBarangExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $kode, String $nama)
    {
        $this->kode = $kode;
        $this->nama = $nama;
    }
    
    public function view(): View
    {
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $sub = Subjenis::where('id_kategori', $this->kode)->get();
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        $data = [
            'waktu' => $waktu,
            'sub' => $sub,
            'nama' => $this->nama,
            'tahun' => $tahun,
            'sejak' => $sejak
        ];
        
        return view('pages.laporan.rekapprice.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('PL-'.$this->nama);

        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setName('Logo');
        // $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        // $drawing->setHeight(50);
        // $drawing->setCoordinates('A1');
        // $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
        
        $totSub = 0;
        $sub = Subjenis::where('id_kategori', $this->kode)->get();
        foreach($sub as $s) {
            $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
            $totSub += $barang->count();
        } 
        
        $range = 4 + $totSub + $sub->count();
        $rangeStr = strval($range);
        $rangeTot = 'C'.$rangeStr;
        $rangeTab = 'E'.$rangeStr;

        $header = 'A4:E4';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $title = 'A1:E2';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:E2')->getFont()->setBold(false)->setSize(12);

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

        $rangeHarga = 'C6:'.$rangeTot;
        $sheet->getStyle($rangeHarga)->getNumberFormat()->setFormatCode('#,##0');

        $rangeValue = 'E6:'.$rangeTab;
        $sheet->getStyle($rangeValue)->getNumberFormat()->setFormatCode('#,##0');
        
        $namaJenis = 'A5:E5';
        $sheet->getStyle($namaJenis)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($namaJenis)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($namaJenis)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

        $no = 0;
        $rangeSub = 6;
        foreach($sub as $s) {
            $barang = Barang::where('id_sub', $s->id)->get();
            if($no != 0) 
                $rangeSub++;

            
            $rangeSub += $barang->count();
            $rangeJen = strval($rangeSub);
            $rangeBar = 'A'.$rangeSub.':E'.$rangeSub;

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
