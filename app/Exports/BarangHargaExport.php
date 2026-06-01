<?php

namespace App\Exports;

use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangHargaExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function view(): View
    {
        $items = Barang::query()
            ->select('barang.*', 'hargabarang.harga_ppn AS harga')
            ->leftJoin('hargabarang', function ($join) {
                $join->on('hargabarang.id_barang', '=', 'barang.id')
                    ->where('hargabarang.id_harga', '=', 'HRG01');
            })
            ->where('barang.tipe', 'TOKO')
            ->get();

        $data = [
            'items' => $items
        ];

        return view('pages.cianjur.barang.template-harga', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Harga_Barang');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(8);

        $items = Barang::query()->where('tipe', 'TOKO')->get();

        $range = 4 + $items->count();
        $rangeStr = strval($range);
        $rangeTab = 'E'.$rangeStr;

        $header = 'A4:E4';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

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

        $rangeTot = 'D5:E'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

        $rangeTable = 'A4:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

    }
}
