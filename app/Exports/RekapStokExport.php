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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapStokExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        $waktu = Carbon::now();
        $waktu = $waktu->format('d F Y, H:i:s');
        
        return view('pages.laporan.excelRekap', [
            'gudang' => Gudang::all(),
            'stok' => StokBarang::with(['barang'])
                        ->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get(),
            'waktu' => $waktu
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $stok = StokBarang::with(['barang'])
                        ->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $range = 5 + $stok->count();
        $rangeStr = strval($range);
        $rangeTot = 'D'.$rangeStr;
        $rangeTab = 'G'.$rangeStr;

        $header = 'A5:G5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $title = 'A1:G3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:G3')->getFont()->setBold(false)->setSize(11);

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
            $rangeRow = 'A'.$i.':G'.$i;
            $sheet->getStyle($rangeRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('d6d7e2');
        }

        $rangeBarang = 'D5:'.$rangeTot;
        $sheet->getStyle($rangeBarang)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');
    } 
}
