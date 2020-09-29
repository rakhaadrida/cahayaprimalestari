<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\StokBarang;
use App\Gudang;
use Illuminate\Support\Facades\DB;

class RekapStokExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        return view('pages.laporan.excelRekap', [
            'gudang' => Gudang::all(),
            'stok' => StokBarang::with(['barang'])
                        ->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $header = 'A4:G4';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $title = 'A1:G2';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
    }
}
