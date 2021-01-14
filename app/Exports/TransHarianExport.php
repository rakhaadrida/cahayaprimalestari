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
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransHarianExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $tanggal, String $tanggalStr)
    {
        $this->tanggal = $tanggal;
        $this->tanggalStr = $tanggalStr;
    }

    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';
        $items = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('tgl_so', $this->tanggal)->orderBy('id_sales')->orderBy('id')->get();

        $data = [
            'waktu' => $waktu,
            'awal' => $this->tanggalStr,
            'akhir' => $this->tanggalStr,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items
        ];
        
        return view('pages.receivable.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('TH-'.$this->tanggalStr);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
                
        $so = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('tgl_so', $this->tanggal)->get();

        $range = 5 + $so->count();
        $rangeStr = strval($range);
        $rangeTab = 'I'.$rangeStr;

        $header = 'A5:I5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');
        
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $title = 'A1:I3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:I3')->getFont()->setBold(false)->setSize(12);

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
