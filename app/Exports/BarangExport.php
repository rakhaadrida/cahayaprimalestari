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
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function view(): View
    {
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        $items = Barang::select('barang.*', 'jenisbarang.nama AS namaJenis', 'subjenis.nama AS namaSub')
            ->leftJoin('jenisbarang', 'jenisbarang.id', 'barang.id_kategori')
            ->leftJoin('subjenis', 'subjenis.id', 'barang.id_sub')
            ->withTrashed()
            ->get();

        $data = [
            'waktu' => $waktu,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items
        ];

        return view('pages.barang.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Barang');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(8);

        $items = Barang::withTrashed()->get();

        $range = 4 + $items->count();
        $rangeStr = strval($range);
        $rangeTab = 'H'.$rangeStr;

        $header = 'A4:H4';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $title = 'A1:H2';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:H2')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // $rangeTot = 'G5:G'.$rangeStr;
        // $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

        $rangeTable = 'A4:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

    }
}
