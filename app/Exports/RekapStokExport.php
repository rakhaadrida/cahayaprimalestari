<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\JenisBarang;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapStokExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');
        
        return view('pages.laporan.rekapstok.excel', [
            'jenis' => JenisBarang::All(),
            'gudang' => Gudang::all(),
            'stok' => StokBarang::with(['barang'])
                        ->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get(),
            'waktu' => $waktu
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Rekap-Stok');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
        
        $jenis = JenisBarang::All();
        $stok = StokBarang::with(['barang'])
                        ->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $range = 5 + $stok->count() + $jenis->count();
        $rangeStr = strval($range);
        $rangeTot = 'D'.$rangeStr;
        $rangeTab = 'F'.$rangeStr;

        $header = 'A5:F5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $title = 'A1:F3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:F3')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];

        $rangeTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A6:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

        for($i = 6; $i <= $range; $i+=2) {
            $rangeRow = 'A'.$i.':F'.$i;
            $sheet->getStyle($rangeRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('d6d7e2');
        }

        $rangeBarang = 'D5:'.$rangeTot;
        $sheet->getStyle($rangeBarang)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');
        
        $namaJenis = 'A6:F6';
        $sheet->getStyle($namaJenis)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($namaJenis)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($namaJenis)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

        $no = 0;
        $rangeJenis = 7;
        foreach($jenis as $j) {
            $barang = Barang::where('id_kategori', $j->id)->get();
            if($no != 0) 
                $rangeJenis++;

            
            $rangeJenis += $barang->count();
            $rangeJen = strval($rangeJenis);
            $rangeBar = 'A'.$rangeJenis.':F'.$rangeJenis;

            if($no != $jenis->count() - 1) {
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
