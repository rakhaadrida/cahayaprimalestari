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

class TransAllExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $tglAwal, String $tglAkhir, String $awal, String $akhir, String $bul, String $status, String $stat)
    {
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
        $this->awal = $awal;
        $this->akhir = $akhir;
        $this->bul = $bul;
        $this->status = $status;
        $this->stat = $stat;
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function view(): View
    {
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        if($this->stat == 'ALL')  {
            $status[0] = 'LUNAS';
            $status[1] = 'BELUM LUNAS';
        }
        else {
            $status[0] = $this->stat;
            $status[1] = '';
        }

        $tglAwal = $this->tglAwal;
        $tglAkhir = $this->tglAkhir;

        if($this->bul != 'KOSONG') {
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
            for($i = 0; $i < sizeof($bulan); $i++) {
                if($this->bul == $bulan[$i]) {
                    $month = $i+1;
                    $angkaMonth = $i+1;
                    break;
                }
            }
            if($month < 10)
                $month = '0'.$month;

            $this->awal = $tahun->year.'-'.$month.'-01';
            $this->akhir = $tahun->year.'-'.$month.'-31';

            $tglAwal = '01-'.$month.'-'.$tahun->year;
            if($month == 2)
                $tglAkhir = '28-'.$month.'-'.$tahun->year;
            elseif((($angkaMonth % 2 == 0) && ($angkaMonth < 8)) || (($angkaMonth % 2 != 0) && ($angkaMonth > 8)))
                $tglAkhir = '30-'.$month.'-'.$tahun->year;
            else
                $tglAkhir = '31-'.$month.'-'.$tahun->year;
        }

        $tglAwal = $this->formatTanggal($tglAwal, 'd-M-y');
        $tglAkhir = $this->formatTanggal($tglAkhir, 'd-M-y');

        $begin = Carbon::createFromFormat('Y-m-d', '1899-12-30');

        if($this->status == 'All') {
            $items = AccReceivable::select('ar.id as id', 'ar.*', 'so.tgl_so', 'so.tempo', 'so.kategori', 'so.total', 'customer.nama AS namaCustomer', 'sales.nama AS namaSales')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->whereBetween('tgl_so', [$this->awal, $this->akhir])
                ->where('kategori', 'NOT LIKE', 'Extrana%')
                ->where('kategori', 'NOT LIKE', '%Prime%')
                ->where('so.id_sales', 'SLS01')
                ->orderBy('so.id_sales')
                ->orderBy('customer.nama')->orderBy('id_so')->get();

            $itemsEx = AccReceivable::select('ar.id as id', 'ar.*', 'so.tgl_so', 'so.tempo', 'so.kategori', 'so.total', 'customer.nama AS namaCustomer', 'sales.nama AS namaSales')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->whereBetween('tgl_so', [$this->awal, $this->akhir])
                ->where('kategori', 'LIKE', 'Extrana%')
                ->where('so.id_sales', 'SLS01')
                ->orderBy('so.id_sales')
                ->orderBy('customer.nama')->orderBy('id_so')->get();
        } else {
            $items = AccReceivable::select('ar.id as id', 'ar.*', 'so.tgl_so', 'so.tempo', 'so.kategori', 'so.total', 'customer.nama AS namaCustomer', 'sales.nama AS namaSales')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->whereBetween('tgl_so', [$this->awal, $this->akhir])
                ->where('kategori', 'LIKE', '%Prime%')
                ->where('so.id_sales', 'SLS01')
                ->orderBy('so.id_sales')
                ->orderBy('customer.nama')->orderBy('id_so')->get();

            $itemsEx = NULL;
        }

        $data = [
            'waktu' => $waktu,
            'awal' => $tglAwal,
            'akhir' => $tglAkhir,
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
        $sheet->setTitle('TH-'.$this->awal);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(10);

        if($this->status == 'All') {
            $so = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->select('ar.id as id', 'ar.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->whereBetween('tgl_so', [$this->awal, $this->akhir])
                    ->where('kategori', 'NOT LIKE', '%Prime%')->orderBy('tgl_so')->get();
        } else {
            $so = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->select('ar.id as id', 'ar.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->whereBetween('tgl_so', [$this->awal, $this->akhir])
                    ->where('kategori', 'LIKE', '%Prime%')->orderBy('tgl_so')->get();
        }

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
