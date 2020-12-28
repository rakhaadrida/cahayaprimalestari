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

class TransAllExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $tglAwal, String $tglAkhir,String $awal, String $akhir, String $bul)
    {
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
        $this->awal = $awal;
        $this->akhir = $akhir;
        $this->bul = $bul;
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
            if((($angkaMonth % 2 == 0) && ($angkaMonth < 8)) || (($angkaMonth % 2 != 0) && ($angkaMonth > 8)))
                $tglAkhir = '30-'.$month.'-'.$tahun->year;
            else
                $tglAkhir = '31-'.$month.'-'.$tahun->year;
        }

        $tglAwal = $this->formatTanggal($tglAwal, 'd-M-y');
        $tglAkhir = $this->formatTanggal($tglAkhir, 'd-M-y');

        $items = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereBetween('tgl_so', [$this->awal, $this->akhir])->get();

        $data = [
            'waktu' => $waktu,
            'awal' => $tglAwal,
            'akhir' => $tglAkhir,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'items' => $items
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
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
                
        $so = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereBetween('tgl_so', [$this->awal, $this->akhir])->get();

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
