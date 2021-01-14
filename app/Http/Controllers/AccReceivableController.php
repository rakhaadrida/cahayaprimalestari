<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\Barang;
use App\Models\HargaBarang;
use App\Models\Gudang;
use App\Models\AR_Retur;
use App\Models\DetilRAR;
use App\Models\StokBarang;
use App\Models\ReturJual;
use App\Models\DetilRJ;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransHarianExport;
use App\Exports\TransAllExport;
use PDF;

class AccReceivableController extends Controller
{
    public function index() {
        $ar = AccReceivable::with(['so'])->orderBy('created_at', 'desc')->get();
        $arOffice = AccReceivable::with(['so'])
                ->select('ar.id', 'ar.id_so', 'ar.keterangan')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->where('id_sales', 'SLS03')->orderBy('tgl_so', 'desc')->get();
        
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $data = [
            'ar' => $ar,
            'arOffice' => $arOffice,
            'barang' => $barang,
            'harga' => $harga,
        ];

        return view('pages.receivable.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function show(Request $request) {
        if($request->status == 'ALL')  {
            $status[0] = 'LUNAS';
            $status[1] = 'BELUM LUNAS';
        }
        else {
            $status[0] = $request->status;
            $status[1] = '';
        }

        $awal = $request->tglAwal;
        $akhir = $request->tglAkhir;

        if($awal != NULL) {
            $awal = $this->formatTanggal($awal, 'Y-m-d');
            $akhir = $this->formatTanggal($akhir, 'Y-m-d');
        }

        $isi = 2;
        if(($request->bulan == '') && ($request->tglAwal == ''))
            $isi = 1;

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        for($i = 0; $i < sizeof($bulan); $i++) {
            if($request->bulan == $bulan[$i]) {
                $month = $i+1;
                break;
            }
            else
                $month = '';
        }

        if($isi == 1) {
            $ar = AccReceivable::with(['so'])->whereIn('keterangan', [$status[0], $status[1]])
                ->orderBy('created_at', 'desc')->get();

            $arOffice = AccReceivable::with(['so'])
                ->select('ar.id', 'ar.id_so', 'ar.keterangan')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->where('id_sales', 'SLS03')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->orderBy('tgl_so', 'desc')->get();
        } else {
            $ar = AccReceivable::with(['so'])->join('so', 'so.id', '=', 'ar.id_so')
                ->select('*', 'so.id as id_so', 'ar.id as id')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('so.tgl_so', $month)
                    ->orWhereBetween('so.tgl_so', [$awal, $akhir]);
                })->orderBy('tgl_so', 'desc')->get();

            $arOffice = AccReceivable::with(['so'])
                ->join('so', 'so.id', '=', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->select('*', 'so.id as id_so', 'ar.id as id')
                ->where('id_sales', 'SLS03')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('so.tgl_so', $month)
                    ->orWhereBetween('so.tgl_so', [$awal, $akhir]);
                })->orderBy('tgl_so', 'desc')->get();
        }

        // return response()->json($ar);

        $barang = Barang::All();
        $harga = HargaBarang::All();
        
        $data = [
            'ar' => $ar,
            'arOffice' => $arOffice,
            'bulan' => $request->bulan,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir,
            'status' => $request->status,
            'barang' => $barang,
            'harga' => $harga,
        ];

        return view('pages.receivable.show', $data);
    }

    public function createCicil($id) {
        $item = AccReceivable::where('id_so', $id)->get();
        $retur = AR_Retur::selectRaw('sum(total) as total')->where('id_ar', $item->first()->id)->get();
        $detilar = DetilAR::where('id_ar', $item->first()->id)->get();

        $data = [
            'item' => $item,
            'retur' => $retur,
            'detilar' => $detilar,
        ];

        return view('pages.receivable.detailNew', $data);
    }

    public function process(Request $request) {
        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = DetilAR::selectRaw('max(id_cicil) as id')->whereYear('tgl_bayar', $waktu->year)
                    ->whereMonth('tgl_bayar', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'CIC'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        $tglBayar = $request->{"tgl".$request->kode};
        $tglBayar = $this->formatTanggal($tglBayar, 'Y-m-d');

        $ar = AccReceivable::where('id_so', $request->kode)->first();
        $total = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                    ->select('ar.id', DB::raw('sum(cicil) as totCicil'))
                    ->where('ar.id_so', $request->kode)
                    ->groupBy('ar.id')->get();
        $so = SalesOrder::where('id', $request->kode)->get();
        $retur = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                ->selectRaw('sum(total) as totRetur')
                ->where('id_so', $request->kode)->get();

        if($total->count() == 0) 
            $totCicil = 0;
        else 
            $totCicil = $total[0]->totCicil;

        if($retur->count() == 0) 
            $totRetur = 0;
        else 
            $totRetur = $retur[0]->totRetur;

        if($so[0]->total == str_replace(",", "", $request->{"cicil".$request->kode}) + $totCicil + $totRetur)
                $status = 'LUNAS';
            else 
                $status = 'BELUM LUNAS';

        // $ar->{'retur'} = (int) str_replace(",", "", $request->{"ret".$arrKode[$i]});
        $ar->{'keterangan'} = $status;
        $ar->save();

        DetilAR::create([
            'id_ar' => $ar->{'id'},
            'id_cicil' => $newcode,
            'tgl_bayar' => $tglBayar,
            'cicil' => (int) str_replace(",", "", $request->{"cicil".$request->kode})
        ]);

        /*
        if($request->kodeSO != "") {
            $arrKode = explode(",", $request->kodeSO);
            $arrKode = array_unique($arrKode);
            sort($arrKode);
            for($i = 0; $i < sizeof($arrKode); $i++) {
                $ar = AccReceivable::where('id_so', $arrKode[$i])->first();
                $total = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                            ->select('ar.id', DB::raw('sum(cicil) as totCicil'))
                            ->where('ar.id_so', $arrKode[$i])
                            ->groupBy('ar.id')->get();
                $so = SalesOrder::where('id', $arrKode[$i])->get();

                if($total->count() == 0) 
                    $totCicil = 0;
                else 
                    $totCicil = $total[$i]->totCicil;

                if($so[0]->total == str_replace(",", "", $request->{"cic".$arrKode[$i]}))
                        $status = 'LUNAS';
                    else 
                        $status = 'BELUM LUNAS';

                $ar->{'retur'} = (int) str_replace(",", "", $request->{"ret".$arrKode[$i]});
                $ar->{'keterangan'} = $status;
                $ar->save();

                DetilAR::create([
                    'id_ar' => $ar->{'id'},
                    'id_cicil' => $newcode,
                    'tgl_bayar' => Carbon::now()->toDateString(),
                    'cicil' => (int) str_replace(",", "", $request->{"cic".$arrKode[$i]}) - $totCicil
                ]);
            }
        }*/

        return redirect()->route('ar');
    }

    public function createRetur($id) {
        $item = AccReceivable::where('id_so', $id)->get();
        $retur = DetilRAR::join('ar_retur', 'ar_retur.id', 'detilrar.id_retur')
                ->where('id_ar', $item->first()->id)->orderBy('tgl_retur', 'asc')->get();
        $total = AR_Retur::selectRaw('sum(total) as total')->where('id_ar', $item->first()->id)->get();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $data = [
            'item' => $item,
            'retur' => $retur,
            'total' => $total,
            'barang' => $barang,
            'harga' => $harga,
        ];

        return view('pages.receivable.returNew', $data);
    }

    public function retur(Request $request) {
        $jumBaris = $request->jumBaris;
        $gudang = Gudang::where('tipe', 'RETUR')->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = AR_Retur::selectRaw('max(id) as id')->whereYear('tanggal', $waktu->year)
                    ->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'RTT'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        // $total = (str_replace(".", "", $request->{"harga".$request->kode}) * 
        //         $request->{"qty".$request->kode}) - str_replace(".", "", $request->{"diskonRp".$request->kode});

        AR_Retur::create([
            'id' => $newcode,
            'id_ar' => $request->kode,
            'tanggal' => $tanggal,
            'total' => 0,
            'id_user' => Auth::user()->id
        ]);

        $lastcodeRJ = ReturJual::selectRaw('max(id) as id')->whereYear('tanggal', $waktu->year)
                    ->whereMonth('tanggal', $month)->get();
        $lastnumberRJ = (int) substr($lastcodeRJ->first()->id, 7, 4);
        $lastnumberRJ++;
        $newcodeRJ = 'RTJ'.$tahun.$bulan.sprintf('%04s', $lastnumberRJ);

        ReturJual::create([
            'id' => $newcodeRJ,
            'tanggal' => Carbon::now('+07:00')->toDateString(),
            'id_customer' => $request->kodeCustomer,
            'status' => 'LENGKAP'
        ]);

        $lastcodeKRM = DetilRJ::selectRaw('max(id_kirim) as id')->whereYear('tgl_kirim', $waktu->year)
                    ->whereMonth('tgl_kirim', $month)->get();;
        $lastnumberKRM = (int) substr($lastcodeKRM->first()->id, 7, 4);
        $lastnumberKRM++;
        $newcodeKRM = 'KRM'.$tahun.$bulan.sprintf("%04s", $lastnumberKRM);
        
        $total = 0;
        for($i = 0; $i < $jumBaris; $i++) {
            if(($request->{"kodeBarang".$request->kode}[$i] != '') && ($request->{"qty".$request->kode}[$i] != '')) {
                $tglRetur = $request->{"tglRetur".$request->kode}[$i];
                $tglRetur = $this->formatTanggal($tglRetur, 'Y-m-d');

                DetilRAR::create([
                    'id_retur' => $newcode,
                    'id_barang' => $request->{"kodeBarang".$request->kode}[$i],
                    'tgl_retur' => $tglRetur,
                    'qty' => $request->{"qty".$request->kode}[$i],
                    'harga' => str_replace(".", "", $request->{"harga".$request->kode}[$i]),
                    'diskon' => $request->{"diskon".$request->kode}[$i],
                    'diskonRp' => str_replace(".", "", $request->{"diskonRp".$request->kode}[$i]),
                ]);

                DetilRJ::create([
                    'id_retur' => $newcodeRJ,
                    'id_barang' => $request->{"kodeBarang".$request->kode}[$i],
                    'id_kirim' => $newcodeKRM,
                    'tgl_kirim' => Carbon::now('+07:00')->toDateString(),
                    'qty_retur' => $request->{"qty".$request->kode}[$i],
                    'qty_kirim' => NULL,
                    'potong' => $request->{"qty".$request->kode}[$i]
                ]);

                $stok = StokBarang::where('id_barang', $request->{"kodeBarang".$request->kode}[$i])
                                ->where('id_gudang', $gudang[0]->id)
                                ->where('status', 'F')->first();
                    
                if($stok == NULL) {
                    StokBarang::create([
                        'id_barang' => $request->{"kodeBarang".$request->kode}[$i],
                        'id_gudang' => $gudang[0]->id,
                        'status' => 'F',
                        'stok' => $request->{"qty".$request->kode}[$i]
                    ]);
                } else {
                    $stok->{'stok'} += $request->{"qty".$request->kode}[$i];
                    $stok->save();
                }

                $total += str_replace(".", "", $request->{"netto".$request->kode}[$i]);
            }
        }

        $ret = AR_Retur::where('id', $newcode)->first();
        $ret->{'total'} = $total;
        $ret->save();

        return redirect()->route('ar');
    }

    public function cetak(Request $request) {
        $awal = $request->tglAwal;
        $awal = $this->formatTanggal($awal, 'Y-m-d');
        $akhir = $request->tglAkhir;
        $akhir = $this->formatTanggal($akhir, 'Y-m-d');
        $tahun = Carbon::now('+07:00');
        $waktu = $tahun->format('d F Y, H:i:s');

        if($request->bulan != '') {
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
            for($i = 0; $i < sizeof($bulan); $i++) {
                if($request->bulan == $bulan[$i]) {
                    $month = $i+1;
                    $angkaMonth = $i+1;
                    break;
                }    
            }
            if($month < 10)
                $month = '0'.$month;

            $awal = $tahun->year.'-'.$month.'-01';
            $akhir = $tahun->year.'-'.$month.'-31';

            $request->tglAwal = '01-'.$month.'-'.$tahun->year;
            if((($angkaMonth % 2 == 0) && ($angkaMonth < 8)) || (($angkaMonth % 2 != 0) && ($angkaMonth > 8)))
                $request->tglAkhir = '30-'.$month.'-'.$tahun->year;
            else
                $request->tglAkhir = '31-'.$month.'-'.$tahun->year;
        }

        $request->tglAwal = $this->formatTanggal($request->tglAwal, 'd-M-y');
        $request->tglAkhir = $this->formatTanggal($request->tglAkhir, 'd-M-y');

        $items = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereBetween('tgl_so', [$awal, $akhir])->orderBy('id_sales')->orderBy('id')->get();
        
        $data = [
            'items' => $items,
            'awal' => $request->tglAwal,
            'akhir' => $request->tglAkhir, 
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.receivable.cetak', $data)
                ->setPaper('a4', 'landscape');
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    }

    public function cetakNow(Request $request) {
        $tahun = Carbon::now('+07:00');
        $waktu = $tahun->format('d F Y, H:i:s');
        $tanggal = Carbon::now()->toDateString();

        $items = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('tgl_so', $tanggal)->orderBy('id_sales')->orderBy('id')->get();

        $tanggal = $this->formatTanggal($tanggal, 'd-M-y');
        
        $data = [
            'items' => $items,
            'awal' => $tanggal,
            'akhir' => $tanggal, 
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.receivable.cetak', $data)
                ->setPaper('a4', 'landscape');
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    }

    public function excel(Request $request) {
        $tglAwal = $request->tglAwal;
        $awal = $this->formatTanggal($request->tglAwal, 'Y-m-d');
        $tglAkhir= $request->tglAkhir;
        $akhir = $this->formatTanggal($request->tglAkhir, 'Y-m-d');
        if($request->bulan == '')
            $bul = 'KOSONG';
        else
            $bul = $request->bulan;

        if($request->tglAwal == '')
            $tglAwal = 'KOSONG';
        else
            $tglAwal = $request->tglAwal;
        
        if($request->tglAkhir == '')
            $tglAkhir = 'KOSONG';
        else
            $tglAkhir = $request->tglAkhir;

        return Excel::download(new TransAllExport($tglAwal, $tglAkhir, $awal, $akhir, $bul), 'trans-bulanan.xlsx');
    }

    public function excelNow() {
        $tanggal = Carbon::now()->toDateString();
        $tanggalStr = $this->formatTanggal($tanggal, 'd-M-y');

        return Excel::download(new TransHarianExport($tanggal, $tanggalStr), 'trans-harian.xlsx');
    }
}
