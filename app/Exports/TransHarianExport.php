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
use App\Models\AccReceivable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransHarianExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $tanggal, String $tanggalStr, String $status)
    {
        $this->tanggal = $tanggal;
        $this->tanggalStr = $tanggalStr;
        $this->status = $status;
    }

    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';
        $begin = Carbon::createFromFormat('Y-m-d', '1899-12-30');

        if($this->status == 'All') {
            $items = AccReceivable::select('ar.id as id', 'ar.*', 'so.tgl_so', 'so.tempo', 'so.kategori', 'so.total', 'customer.nama AS namaCustomer', 'sales.nama AS namaSales')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('tgl_so', $this->tanggal)
                ->where('kategori', 'NOT LIKE', 'Extrana%')
                ->where('kategori', 'NOT LIKE', '%Prime%')
                ->orderBy('so.id_sales')
                ->orderBy('customer.nama')->orderBy('id_so')->get();

            $itemsEx = AccReceivable::select('ar.id as id', 'ar.*', 'so.tgl_so', 'so.tempo', 'so.kategori', 'so.total', 'customer.nama AS namaCustomer', 'sales.nama AS namaSales')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('tgl_so', $this->tanggal)
                ->where('kategori', 'LIKE', 'Extrana%')
                ->orderBy('so.id_sales')
                ->orderBy('customer.nama')->orderBy('id_so')->get();
        } else {
            $items = AccReceivable::select('ar.id as id', 'ar.*', 'so.tgl_so', 'so.tempo', 'so.kategori', 'so.total', 'customer.nama AS namaCustomer', 'sales.nama AS namaSales')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('tgl_so', $this->tanggal)->where('kategori', 'LIKE', '%Prime%')
                ->orderBy('so.id_sales')
                ->orderBy('customer.nama')->orderBy('id_so')->get();

            $itemsEx = NULL;
        }

        $data = [
            'waktu' => $waktu,
            'awal' => $this->tanggalStr,
            'akhir' => $this->tanggalStr,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items,
            'itemsEx' => $itemsEx,
            'aw' => $begin
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

        if($this->status == 'All') {
            $so = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])->where('kategori', 'NOT LIKE', '%Prime%')
                    ->where('tgl_so', $this->tanggal)->get();
        } else {
            $so = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])->where('kategori', 'LIKE', '%Prime%')
                    ->where('tgl_so', $this->tanggal)->get();
        }

        // $so = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
        //         ->where('tgl_so', $this->tanggal)->get();

        $range = 5 + $so->count();
        $rangeStr = strval($range);
        $rangeTab = 'L'.$rangeStr;

        $header = 'A5:L5';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($header)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffddb5');

        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');
        $title = 'A1:L3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:L3')->getFont()->setBold(false)->setSize(12);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('F6:G'.$rangeStr)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_XLSX15);

        $rangeTot = 'H6:K'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');

        $rangeTable = 'A5:'.$rangeTab;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $rangeIsiTable = 'A6:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

    }
}
