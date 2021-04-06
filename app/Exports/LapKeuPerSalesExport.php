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
use App\Models\Sales;
use App\Models\SalesOrder;
use App\Models\JenisBarang;
use App\Models\DetilSO;
use App\Models\DetilRAR;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LapKeuPerSalesExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $id, String $nama, String $urut, String $tahun, String $month)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->urut = $urut;
        $this->tahun = $tahun;
        $this->month = $month;
    }
    
    public function view(): View
    {
        $jenis = JenisBarang::All();
        $sales = Sales::All();
        $diskon = SalesOrder::selectRaw('sum(diskon) as diskon')->whereYear('tgl_so', $this->tahun)
            ->whereMonth('tgl_so', $this->month)->get();

        $items = DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                ->join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('so.id_sales', 'sales.nama', 'barang.id_kategori', DB::raw('sum(harga * qty - diskonRp) as total'))
                ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                ->whereYear('so.tgl_so', $this->tahun)
                ->whereMonth('so.tgl_so', $this->month)
                ->groupBy('so.id_sales', 'barang.id_kategori')
                ->get();

        $retur = DetilRAR::join('barang', 'barang.id', 'detilrar.id_barang')
                ->join('ar_retur', 'ar_retur.id', 'detilrar.id_retur')
                ->join('ar', 'ar.id', 'ar_retur.id_ar')->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id' , 'customer.id_sales')
                ->select('so.id_sales', 'barang.id_kategori', DB::raw('sum((qty * harga) - diskonRp) as total'))
                ->whereNotIn('so.status', ['BATAL', 'LIMIT', 'RETUR']) 
                ->whereYear('so.tgl_so', $this->tahun)
                ->whereMonth('so.tgl_so', $this->month)
                ->groupBy('so.id_sales', 'barang.id_kategori')
                ->get();
        
        $kat = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                ->whereYear('so.tgl_so', $this->tahun)->whereMonth('so.tgl_so', $this->month)
                ->groupBy('id_kategori')->orderBy('id_kategori')->get();
    
        $kategori = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->where('id_sales', $this->id)
                ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                ->whereYear('so.tgl_so', $this->tahun)->whereMonth('so.tgl_so', $this->month)
                ->groupBy('id_kategori')->orderBy('id_kategori')->get();
        $barang = DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                ->join('so', 'so.id', 'detilso.id_so')
                ->select('id_so as id', 'detilso.*')
                ->where('id_sales', $this->id)->where('qty', '!=', 0)
                ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                ->whereYear('so.tgl_so', $this->tahun)
                ->whereMonth('so.tgl_so', $this->month)
                ->groupBy('id_barang', 'harga', 'diskon')
                ->get();

        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';
        $tanggal = $this->tahun.'-'.$this->month.'-01';
        $bul = Carbon::parse($tanggal)->isoFormat('MMMM');

        $data = [
            'waktu' => $waktu,
            'tahun' => $tahun,
            'sejak' => $sejak,
            'bul' => $bul,
            'items' => $items,
            'retur' => $retur,
            'jenis' => $jenis,
            'sales' => $sales,
            'diskon' => $diskon,
            'kat' => $kat,
            'kategori' => $kategori,
            'nama' => $this->nama,
            'id' => $this->id,
            'tah' => $this->tahun,
            'bulan' => $this->month
        ];

        if($this->id == '0')
            return view('pages.keuangan.excel', $data);
        else
            return view('pages.keuangan.excelHpp', $data);
    }

    public function styles(Worksheet $sheet)
    {
        if($this->id == '0') {
            $sheet->setTitle('Lap-Keu');

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
            $drawing->setHeight(50);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
            $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5); 
            
            $alpha = range('A', 'Z');
            $sales = Sales::All();
            $jenis = JenisBarang::All();
            $kat = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                ->whereYear('so.tgl_so', $this->tahun)->whereMonth('so.tgl_so', $this->month)
                ->groupBy('id_kategori')->orderBy('id_kategori')->get();

            $lastAlpha = 3 + $jenis->count();
            $range = 4 + ($sales->count() * 4) + 10 + $kat->count();
            $rangeStr = strval($range);
            $rangeTot = 'C'.$rangeStr;
            $rangeTab = $alpha[$lastAlpha].$rangeStr;

            $header = 'A4:'.$alpha[$lastAlpha].'4';
            $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
            
            $sheet->mergeCells('A1:'.$alpha[$lastAlpha].'1');
            $sheet->mergeCells('A2:'.$alpha[$lastAlpha].'2');
            $title = 'A1:'.$alpha[$lastAlpha].'2';
            $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A2:'.$alpha[$lastAlpha].'2')->getFont()->setBold(false)->setSize(12);

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

            $rangeHarga = 'D5:'.$alpha[$lastAlpha].$rangeStr;
            $sheet->getStyle($rangeHarga)->getNumberFormat()->setFormatCode('#,##0');
            
            $namaJenis = 'A4:'.$alpha[$lastAlpha].'4';
            $sheet->getStyle($namaJenis)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($namaJenis)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($namaJenis)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ffddb5');

            $total = $range - 9 - $kat->count();
            $rangeTotal = strval($total);
            $sheet->getStyle('A'.$rangeTotal.':'.$alpha[$lastAlpha].$rangeStr)->getAlignment()->setHorizontal('right');
            $sheet->getStyle('A'.$rangeTotal.':'.$alpha[$lastAlpha].$rangeStr)->getFont()->setBold(true)->setSize(12);
            
            $nama = 4 + ($sales->count() * 4);
            $rangeNama = strval($nama);
            $sheet->getStyle('A5:B'.$rangeNama)->getAlignment()->setVertical('center');
            
            $strRev = '='.$alpha[$lastAlpha].'5'; $strHpp = '='.$alpha[$lastAlpha].'6'; 
            $strRetur = '='.$alpha[$lastAlpha].'7';
            for($i = 8; $i <= (4 + ($sales->count() * 4)); $i += 4) {
                $laba = 'C'.$i.':'.$alpha[$lastAlpha].$i;
                $sheet->getStyle($laba)->getFont()->setBold(true);
                $sheet->getStyle($laba)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00');

                $revenue = $i - 3;
                $sheet->setCellValue($alpha[$lastAlpha].$revenue, '=SUM(D'.$revenue.':O'.$revenue.')');

                $hpp = $i - 2;
                $sheet->setCellValue($alpha[$lastAlpha].$hpp, '=SUM(D'.$hpp.':O'.$hpp.')');

                $retur = $i - 1;
                $sheet->setCellValue($alpha[$lastAlpha].$retur, '=SUM(D'.$retur.':O'.$retur.')');

                if($i != 8) {
                    $strRev = $strRev.'+'.$alpha[$lastAlpha].$revenue;
                    // $strHpp = $strHpp.'+'.$alpha[$lastAlpha].$hpp;
                    $strRetur = $strRetur.'+'.$alpha[$lastAlpha].$retur;
                }
                
                $sheet->setCellValue($alpha[$lastAlpha].$i, '=SUM(D'.$i.':O'.$i.')');

                $urut = range('D', 'Z');
                for($j = 0; $j < $jenis->count(); $j++) {
                    $sheet->setCellValue($urut[$j].$i, '='.$urut[$j].$revenue.'-'.$urut[$j].$hpp.'-'.$urut[$j].$retur);
                }
            }

            $cellAwalHpp = strval($total + 1);
            $strTotHpp = $alpha[$lastAlpha].$cellAwalHpp;
            for($i = ($total + 2); $i <= ($total + $kat->count()); $i++) {
                $strTotHpp = $strTotHpp.'-'.$alpha[$lastAlpha].$i;
            }

            // $cellHpp = strval($total + 1);
            $cellRetur = strval($range - 8);
            $cellLaba = strval($range - 7);
            $cellDapat = strval($range - 6);
            $cellTotDapat = strval($range - 5);
            $cellBG = strval($range - 4);
            $cellBJ = strval($range - 3);
            $cellBL = strval($range - 2);
            $cellPC = strval($range - 1);
            $sheet->setCellValue($alpha[$lastAlpha].$rangeTotal, $strRev);
            // $sheet->setCellValue($alpha[$lastAlpha].$cellHpp, $strHpp);
            $sheet->setCellValue($alpha[$lastAlpha].$cellRetur, $strRetur);

            // $strLaba = '='.$alpha[$lastAlpha].$rangeTotal.'-'.$alpha[$lastAlpha].$cellHpp.'-'.$alpha[$lastAlpha].$cellRetur;
            $strLaba = '='.$alpha[$lastAlpha].$rangeTotal.'-'.$strTotHpp.'-'.$alpha[$lastAlpha].$cellRetur;

            $sheet->setCellValue($alpha[$lastAlpha].$cellLaba, $strLaba);
            $sheet->setCellValue($alpha[$lastAlpha].$cellTotDapat, '='.$alpha[$lastAlpha].$cellLaba.'+'.$alpha[$lastAlpha].$cellDapat);

            $strGT = '='.$alpha[$lastAlpha].$cellTotDapat.'-'.$alpha[$lastAlpha].$cellBG.'-'.$alpha[$lastAlpha].$cellBJ.'-'.$alpha[$lastAlpha].$cellBL.'-'.$alpha[$lastAlpha].$cellPC;
            $sheet->setCellValue($alpha[$lastAlpha].$rangeStr, $strGT);

            $rangeLaba = 'A'.$cellLaba.':'.$alpha[$lastAlpha].$cellLaba;
            $rangeTotDapat = 'A'.$cellTotDapat.':'.$alpha[$lastAlpha].$cellTotDapat;
            $rangeGT = 'A'.$rangeStr.':'.$alpha[$lastAlpha].$rangeStr;
            $sheet->getStyle($rangeLaba)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FABF8F');
            $sheet->getStyle($rangeTotDapat)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('92D050');
            $sheet->getStyle($rangeGT)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('8DB4E2');
            // $sheet->setCellValue('D6', "='HPP-Ibu'!H129");

        } else {
            $sheet->setTitle('HPP-'.$this->nama);

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
            $drawing->setHeight(50);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
            $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5); 
            $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(13); 
            
            $jenis = JenisBarang::All();
            $kategori = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->where('id_sales', $this->id)
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                    ->where('id_customer', '!=', 'CUS1071')
                    ->whereYear('so.tgl_so', $this->tahun)->whereMonth('so.tgl_so', $this->month)
                    ->groupBy('id_kategori')->orderBy('id_kategori')->get();
            $barang = DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                    ->join('so', 'so.id', 'detilso.id_so')
                    ->select('id_so as id', 'detilso.*')
                    // ->where('id_sales', $this->id)->where('qty', '!=', 0)
                    // ->where('id_kategori', $k->id_kategori)
                    ->where('id_kategori', $this->id)->where('qty', '!=', 0)
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                    ->where('id_customer', '!=', 'CUS1071')
                    ->whereYear('so.tgl_so', $this->tahun)
                    ->whereMonth('so.tgl_so', $this->month)
                    // ->groupBy('id_barang', 'harga', 'diskon')
                    ->groupBy('id_barang', 'harga')
                    ->get();

            // $range = 4 + $barang->count() + ($kategori->count() * 3) + ($kategori->count() - 1) - 2;
            $range = 5 + $barang->count();
            $rangeStr = strval($range);
            $rangeTot = 'C'.$rangeStr;
            $rangeTab = 'H'.$rangeStr;

            $header = 'A4:H4';
            $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($header)->getAlignment()->setHorizontal('center');
            
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

            $rangeTable = 'A4:'.$rangeTab;
            $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

            $rangeIsiTable = 'A5:'.$rangeTab;
            $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

            $rangeHarga = 'C5:H'.$rangeStr;
            $sheet->getStyle($rangeHarga)->getNumberFormat()->setFormatCode('#,##0');

            $rangeDiskon = 'F5:F'.$rangeStr;
            $sheet->getStyle($rangeDiskon)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            
            $namaJenis = 'A4:H4';
            $sheet->getStyle($namaJenis)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle($namaJenis)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($namaJenis)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ffddb5');

            // $lapKeu = range('D', 'Z'); $lapIndex = 0;
            $lapKeu = range('A', 'Z'); $lapIndex = 3 + $jenis->count();
            $TH = 4; $rowTotal = 5; $blankFirst = 5; $blankSecond = 5;
            // foreach($kategori as $k) {
                $items = DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                        ->join('so', 'so.id', 'detilso.id_so')
                        ->select('id_so as id', 'detilso.*')
                        // ->where('id_sales', $this->id)->where('qty', '!=', 0)
                        // ->where('id_kategori', $k->id_kategori)
                        ->where('id_kategori', $this->id)->where('qty', '!=', 0)
                        ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                        ->whereYear('so.tgl_so', $this->tahun)
                        ->whereMonth('so.tgl_so', $this->month)
                        // ->groupBy('id_barang', 'harga', 'diskon')
                        ->groupBy('id_barang', 'harga')
                        ->get();
                
                $TH += ($items->count() + 4);
                $strTH = strval($TH);
                
                $TR = 'A'.$strTH.':H'.$strTH;

                /* if($k->id_kategori != $kategori[$kategori->count()-1]->id_kategori) {
                    $sheet->getStyle($TR)->getFont()->setBold(true)->setSize(12);
                    $sheet->getStyle($TR)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle($TR)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('ffddb5');
                } */

                $awal = $rowTotal;
                $strAwal = strval($awal);
                // $rowTotal += $items->count();
                // $strTotal = strval($rowTotal);
                // $strTotOne = strval($rowTotal-1);
                // $RT = 'A'.$strTotal.':H'.$strTotal; 

                $rowTotal = $range;
                $strTotal = $rangeStr;
                $strTotOne = strval($rangeStr-1);
                $RT = 'A'.$rangeStr.':H'.$rangeStr; 

                $sheet->getStyle($RT)->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle($RT)->getAlignment()->setHorizontal('right');
                $sheet->getStyle($RT)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF00');

                $strNama = 'HPP-'.$this->nama;
                $sheet->setCellValue('H'.$strTotal, '=SUM(H'.$strAwal.':H'.$strTotOne.')');
                $sheet->setCellValue("'Lap-Keu'!".$lapKeu[$lapIndex].$this->urut, "='HPP-{$this->nama}'!H".$strTotal);

                for($i = $awal; $i < $rowTotal; $i++) {
                    $sheet->setCellValue('G'.$i, '=E'.$i.'*F'.$i);
                    $sheet->setCellValue('H'.$i, '=E'.$i.'-G'.$i);
                }

                /* $rowTotal += 4;

                $blankFirst += ($items->count() + 1);
                $blankSecond += ($items->count() + 2);
                $strBF = strval($blankFirst);
                $strBS = strval($blankSecond);
                $blank = 'A'.$strBF.':H'.$strBS; 

                $styleBlank = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => '',
                        ],
                    ],
                ];

                $sheet->getStyle($blank)->applyFromArray($styleBlank);

                $blankFirst += 3;
                $blankSecond += 2; */
                // $lapIndex++;
            // }
        }
    } 
}
