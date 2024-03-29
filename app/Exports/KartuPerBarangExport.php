<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\StokBarang;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\DetilBM;
use App\Models\DetilSO;
use App\Models\DetilTB;
use App\Models\DetilRJ;
use App\Models\DetilRB;
use App\Models\DetilRT;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class KartuPerBarangExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function __construct(String $kode, String $awal, String $akhir) {
        $this->kode = $kode;
        $this->awal = $awal;
        $this->akhir = $akhir;
    }
    
    public function view(): View {
        $now = Carbon::now('+07:00')->toDateString();
        $barang = Barang::All();
        $gudang = Gudang::All();
        $tglAwal = $this->awal;
        $tglAkhir = $this->akhir;
        $tahun = Carbon::now('+07:00');
        $sejak = '2020';

        $itemsBRG = Barang::where('id', $this->kode)->get();
        $itemsBM = DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                    ->select('id', 'id_bm', 'id_barang', 'tanggal', 'barangmasuk.created_at', 'detilbm.diskon as id_asal', 'disPersen as id_tujuan', 'qty')
                    ->where('id_barang', $this->kode)
                    ->whereHas('bm', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    });
        $itemsSO = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->select('id', 'id_so', 'id_barang', 'tgl_so as tanggal', 'so.created_at', 'detilso.diskon as id_asal', 'diskonRp as id_tujuan')->selectRaw('sum(qty) as qty')->where('id_barang', $this->kode)
                    ->whereHas('so', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tgl_so', [$this->awal, $this->akhir])
                        ->whereNotIn('status', ['BATAL', 'LIMIT']);
                    })->groupBy('id_so', 'id_barang');
        $itemsRJ = DetilRJ::join('returjual', 'returjual.id', 'detilrj.id_retur')
                    ->select('id', 'id_retur', 'id_barang', 'tanggal', 'returjual.created_at', 'tgl_kirim as id_asal', 'id_kirim as id_tujuan', 'qty_retur as qty')
                    ->where('id_barang', $this->kode)
                    ->whereHas('retur', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    });
        $itemsKRJ = DetilRJ::join('returjual', 'returjual.id', 'detilrj.id_retur')
                    ->select('id_kirim as id', 'id_retur', 'id_barang', 'tgl_kirim as tanggal', 'returjual.created_at', 'tanggal as id_asal', 'potong as id_tujuan', 'qty_kirim as qty')->where('id_barang', $this->kode)->where('qty_kirim', '!=', 0)
                    ->whereHas('retur', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    });
        $itemsRB = DetilRB::join('returbeli', 'returbeli.id', 'detilrb.id_retur')
                    ->select('id', 'id_retur', 'id_barang', 'tanggal', 'returbeli.created_at', 'detilrb.created_at as id_asal', 'returbeli.updated_at as id_tujuan', 'qty_retur as qty')
                    ->where('id_barang', $this->kode)
                    ->whereHas('returbeli', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    });
        $itemsTRB = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                    ->select('id_terima as id', 'id_retur', 'id_barang', 'tanggal', 'returterima.created_at', 'qty_batal as id_asal', 'potong as id_tujuan', 'qty_terima as qty')->where('id_barang', $this->kode)
                    ->whereHas('returterima', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir]);
                    });
        $items = DetilTB::join('transferbarang', 'transferbarang.id', 'detiltb.id_tb')
                    ->select('id', 'id_tb', 'id_barang', 'tgl_tb as tanggal', 'transferbarang.created_at', 'id_asal', 'id_tujuan', 'qty')->where('id_barang', $this->kode)
                    ->whereHas('tb', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tgl_tb', [$this->awal, $this->akhir]);
                    })->union($itemsBM)->union($itemsSO)->union($itemsRJ)->union($itemsKRJ)
                    ->union($itemsRB)->union($itemsTRB)->orderBy('created_at')->get();

        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->where('id_barang', $this->kode)
                        ->groupBy('id_barang')->get();

        $i = 0;
        $stokAwal = 0;
        foreach($stok as $s) {
            $stokAwal = $s->total;
            $itemsBM = DetilBM::selectRaw('sum(qty) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('bm', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tanggal', [$tglAwal, $now])
                            ->where('status', '!=', 'BATAL');
                        })->get();
            foreach($itemsBM as $bm) {
                $stokAwal -= $bm->qty;
            }

            $itemsSO = DetilSO::selectRaw('sum(qty) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('so', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tgl_so', [$tglAwal, $now])
                            ->whereNotIn('status', ['BATAL', 'LIMIT']);
                        })->get();
            foreach($itemsSO as $so) {
                $stokAwal += $so->qty;
            }

            $itemsRJ = DetilRJ::selectRaw('sum(qty_retur - qty_kirim) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('retur', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tanggal', [$tglAwal, $now])
                            ->where('status', '!=', 'BATAL');
                        })->get();
            foreach($itemsRJ as $rj) {
                $stokAwal -= $rj->qty;
            }

            $itemsRB = DetilRB::selectRaw('sum(qty_retur) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('returbeli', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tanggal', [$tglAwal, $now])
                            ->where('status', '!=', 'BATAL');
                        })->get();
            foreach($itemsRB as $rb) {
                $itemsRT = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                        ->join('returbeli', 'returbeli.id', 'returterima.id_retur')
                        ->selectRaw('sum(qty_terima + qty_batal) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereBetween('returbeli.tanggal', [$tglAwal, $now])->get();

                $stokAwal += ($rb->qty - $itemsRT->first()->qty);
            }

            $i++;
        }

        $data = [
            'barang' => $barang,
            'gudang' => $gudang,
            'itemsBRG' => $itemsBRG,
            'items' => $items,
            'awal' => $tglAwal,
            'akhir' => $tglAkhir,
            'stok' => $stok,
            'stokAwal' => $stokAwal,
            'tahun' => $tahun,
            'sejak' => $sejak
        ];
        
        return view('pages.laporan.kartustok.excel', $data);
    }

    public function styles(Worksheet $sheet) {
        $sheet->setTitle('KS-'.$this->kode);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/backend/img/Logo_CPL.jpg'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        
        $alpha = range('A', 'Z');
        $gudang = Gudang::All();
        $angkaHuruf = $gudang->count() + 11;
        $huruf = $alpha[$angkaHuruf];

        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
        $sheet->mergeCells('A1:'.$huruf.'1');
        $sheet->mergeCells('A2:'.$huruf.'2');
        $sheet->mergeCells('A3:'.$huruf.'3');
        $sheet->mergeCells('A5:'.$huruf.'5');
        $sheet->mergeCells('A6:'.$huruf.'6');

        $title = 'A1:'.$huruf.'3';
        $sheet->getStyle($title)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:'.$huruf.'3')->getFont()->setSize(12);

        $infoBrg = 'A5:'.$huruf.'6';
        $sheet->getStyle($infoBrg)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($infoBrg)->getAlignment()->setHorizontal('left');

        $header = 'A7:'.$huruf.'9';
        $sheet->getStyle($header)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($header)->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle($header)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');

        $tglAwal = $this->awal;
        $tglAkhir = $this->akhir;
        $rowBM = DetilBM::with(['bm', 'barang'])
                    ->where('id_barang', $this->kode)
                    ->whereHas('bm', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    })->count();
        $rowSO = DetilSO::with(['so', 'barang'])
                    ->select('id_so', 'id_barang')
                    ->selectRaw('avg(harga) as harga, sum(qty) as qty')
                    ->where('id_barang', $this->kode)
                    ->whereHas('so', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tgl_so', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    })->groupBy('id_so', 'id_barang')
                    ->get();
        $rowTB = DetilTB::where('id_barang', $this->kode)
                    ->whereHas('tb', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tgl_tb', [$this->awal, $this->akhir]);
                    })->get();
        $rowRJ = DetilRJ::where('id_barang', $this->kode)
                    ->whereHas('retur', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    });
        $rowKRJ = DetilRJ::where('id_barang', $this->kode)->where('qty_kirim', '!=', 0)
                    ->whereHas('retur', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    });
        $rowRB = DetilRB::where('id_barang', $this->kode)
                    ->whereHas('returbeli', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir])
                        ->where('status', '!=', 'BATAL');
                    });
        $rowTRB = DetilRT::where('id_barang', $this->kode)
                    ->whereHas('returterima', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$this->awal, $this->akhir]);
                    });

        $range = 10 + $rowBM + $rowSO->count() + $rowTB->count() + $rowRJ->count() + $rowKRJ->count() + $rowRB->count() + $rowTRB->count() + 2;
        $rangeStr = strval($range);
        $rangeMinOne = strval($range-1);
        $rangeTab = $huruf.$rangeStr;

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $rangeTable = 'A7:'.$huruf.$rangeStr;
        $sheet->getStyle($rangeTable)->applyFromArray($styleArray);

        $stokAwal = 'A10:E10';
        $total = 'A'.$rangeMinOne.':E'.$rangeMinOne;
        $stokAkhir = 'A'.$rangeStr.':E'.$rangeStr;
        $sheet->getStyle($stokAwal)->getFont()->setBold(true);
        $sheet->getStyle($stokAwal)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($total)->getFont()->setBold(true);
        $sheet->getStyle($total)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($stokAkhir)->getFont()->setBold(true);
        $sheet->getStyle($stokAkhir)->getAlignment()->setHorizontal('center');

        $awalRow = 'F10:'.$huruf.'10';
        $totalRow = 'F'.$rangeMinOne.':'.$huruf.$rangeMinOne;
        $akhirRow = 'F'.$rangeStr.':'.$huruf.$rangeStr;
        $akhirFull = 'A'.$rangeStr.':'.$huruf.$rangeStr;

        $sheet->getStyle($awalRow)->getFont()->setBold(true);
        $sheet->getStyle($awalRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($totalRow)->getFont()->setBold(true);
        $sheet->getStyle($totalRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($akhirRow)->getFont()->setBold(true);
        $sheet->getStyle($akhirRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle($akhirFull)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');

        $rangeIsiTable = 'A10:'.$rangeTab;
        $sheet->getStyle($rangeIsiTable)->getFont()->setSize(12);

        for($i = 10; $i <= $range-1; $i+=2) {
            $rangeRow = 'A'.$i.':'.$huruf.$i;
            $sheet->getStyle($rangeRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('d6d7e2');
        }

        $rangeTot = 'S11:S'.$rangeStr;
        $sheet->getStyle($rangeTot)->getNumberFormat()->setFormatCode('#,##0');
    } 
}
