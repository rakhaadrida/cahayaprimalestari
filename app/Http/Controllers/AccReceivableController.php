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
        $arLast = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                // ->join('sales', 'sales.id', 'customer.id_sales')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('ar.id as id', 'ar.*', 'so.kategori', 'so.tgl_so', 'so.tempo', 'so.total', 'customer.nama as namaCust', 'sales.nama as namaSales')
                ->orderBy('ar.updated_at', 'desc')->take(1)->get();

        $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                // ->join('sales', 'sales.id', 'customer.id_sales')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('ar.id as id', 'ar.*', 'so.kategori', 'so.tgl_so', 'so.tempo', 'so.total', 'customer.nama as namaCust', 'sales.nama as namaSales')
                ->where('ar.id', '!=', $arLast->first()->id)->where('keterangan', 'BELUM LUNAS')
                ->orderBy('ar.created_at', 'desc')->get();
        $arOffice = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                // ->join('sales', 'sales.id', 'customer.id_sales')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('ar.id as id', 'ar.*', 'so.kategori', 'so.tgl_so', 'so.tempo', 'so.total', 'customer.nama as namaCust', 'sales.nama as namaSales')
                // ->where('id_sales', 'SLS03')->orderBy('tgl_so', 'desc')->get();
                ->where('so.id_sales', 'SLS03')->orderBy('tgl_so', 'desc')->get();

        $data = [
            'ar' => $ar,
            'arOffice' => $arOffice,
            'arLast' => $arLast
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
            if(ucfirst($request->bulan) == $bulan[$i]) {
                $month = $i+1;
                break;
            }
            else
                $month = '';
        }

        if($isi == 1) {
            $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                // ->join('sales', 'sales.id', 'customer.id_sales')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('ar.id as id', 'ar.*', 'so.kategori', 'so.tgl_so', 'so.tempo', 'so.total', 'customer.nama as namaCust', 'sales.nama as namaSales')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->orderBy('ar.created_at', 'desc')->get();

            $arOffice = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                // ->join('sales', 'sales.id', 'customer.id_sales')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('ar.id as id', 'ar.*', 'so.kategori', 'so.tgl_so', 'so.tempo', 'so.total', 'customer.nama as namaCust', 'sales.nama as namaSales')
                // ->where('id_sales', 'SLS03')
                ->where('so.id_sales', 'SLS03')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->orderBy('tgl_so', 'desc')->get();
        } else {
            $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                // ->join('sales', 'sales.id', 'customer.id_sales')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('ar.id as id', 'ar.*', 'so.kategori', 'so.tgl_so', 'so.tempo', 'so.total', 'customer.nama as namaCust', 'sales.nama as namaSales')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('so.tgl_so', $month)
                    ->orWhereBetween('so.tgl_so', [$awal, $akhir]);
                })->orderBy('tgl_so', 'desc')->get();

            $arOffice = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                // ->join('sales', 'sales.id', 'customer.id_sales')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->select('ar.id as id', 'ar.*', 'so.kategori', 'so.tgl_so', 'so.tempo', 'so.total', 'customer.nama as namaCust', 'sales.nama as namaSales')
                // ->where('id_sales', 'SLS03')
                ->where('so.id_sales', 'SLS03')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('so.tgl_so', $month)
                    ->orWhereBetween('so.tgl_so', [$awal, $akhir]);
                })->orderBy('tgl_so', 'desc')->get();
        }

        $barang = Barang::All();
        $harga = HargaBarang::All();
        
        $data = [
            'ar' => $ar,
            'arOffice' => $arOffice,
            'bulan' => ucfirst($request->bulan),
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
        $detilar = DetilAR::where('id_ar', $item->first()->id)->orderBy('tgl_bayar')->get();

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

        $tglBayar = $request->tgl;
        $tglBayar = $this->formatTanggal($tglBayar, 'Y-m-d');

        // $ar = AccReceivable::where('id_so', $request->kode)->first();
        // $ar->{'keterangan'} = 'Belum Lunas';
        // $ar->save();

        if($request->kurangAkhir == 0)
            $status = 'LUNAS';
        else 
            $status = 'BELUM LUNAS';

        $items = DetilAR::where('id_ar', $request->kodeAR)->get();
        $j = 0;
        foreach($items as $i) {
            if($j < $request->jumBaris) {
                $tglDetil = $request->tgldetil[$j];
                $tglDetil = $this->formatTanggal($tglDetil, 'Y-m-d');

                if(($tglDetil != $i->tgl_bayar) || ($request->cicildetil[$j] != $i->cicil)) {
                    $i->tgl_bayar = $tglDetil;
                    $i->cicil = str_replace(".", "", $request->cicildetil[$j]);
                    $i->save();
                }
            } else {
                $i->delete();
            }

            $j++;
        }

        $lastcode = DetilAR::selectRaw('max(id_cicil) as id')->whereYear('created_at', $waktu->year)
                    ->whereMonth('created_at', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'CIC'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        if($request->cicil != '') {
            DetilAR::create([
                'id_ar' => $request->kodeAR,
                'id_cicil' => $newcode,
                'tgl_bayar' => $tglBayar,
                'cicil' => (int) str_replace(".", "", $request->cicil)
            ]);
        }

        $arUpdate = AccReceivable::where('id', $request->kodeAR)->first();
        $arUpdate->{'keterangan'} = $status;
        $arUpdate->save();

        return redirect()->route('ar');
    }

    public function batalCicil(Request $request) {
        $items = DetilAR::where('id_ar', $request->kodeAR)->get();
        $j = 0;
        foreach($items as $i) {
            if($j < $request->jumBaris) {
                $tglDetil = $request->tgldetil[$j];
                $tglDetil = $this->formatTanggal($tglDetil, 'Y-m-d');

                if(($tglDetil != $i->tgl_bayar) || ($request->cicildetil[$j] != $i->cicil)) {
                    $i->tgl_bayar = $tglDetil;
                    $i->cicil = str_replace(".", "", $request->cicildetil[$j]);
                    $i->save();
                }
            } else {
                $i->delete();
            }

            $j++;
        }

        $ar = AccReceivable::where('id_so', $request->kode)->first();
        $ar->{'keterangan'} = 'BELUM LUNAS';
        $ar->save();

        return redirect()->route('ar');
    }

    public function createRetur($id) {
        $item = AccReceivable::where('id_so', $id)->get();
        $retur = DetilRAR::join('ar_retur', 'ar_retur.id', 'detilrar.id_retur')
                ->where('id_ar', $item->first()->id)->orderBy('tgl_retur')->orderBy('id_barang')->get();
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

        $tanggal = Carbon::now()->toDateString();
        $item = AR_Retur::where('id_ar', $request->kode)->get();

        if($item->count() == 0) {
            $lastcode = AR_Retur::selectRaw('max(id) as id')->whereYear('tanggal', $waktu->year)
                        ->whereMonth('tanggal', $month)->get();
            $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
            $lastnumber++;
            $newcode = 'RTT'.$tahun.$bulan.sprintf("%04s", $lastnumber);

            AR_Retur::create([
                'id' => $newcode,
                'id_ar' => $request->kode,
                'tanggal' => $tanggal,
                'total' => 0,
                'id_user' => Auth::user()->id
            ]);

            ReturJual::create([
                'id' => $newcode,
                'tanggal' => Carbon::now('+07:00')->toDateString(),
                'id_customer' => $request->kodeCustomer,
                'status' => 'LENGKAP'
            ]);

            $kodeAR = $newcode;
        } else {
            $kodeAR = $item->first()->id;
        }

        $items = DetilRAR::where('id_retur', $request->kodeRet)->orderBy('tgl_retur')->get();
        $returJual = DetilRJ::where('id_retur', $request->kodeRet)->orderBy('tgl_kirim')->get();
        $totalAwal = 0; $kodeBarang = []; 
        if($items->count() != $request->jumAwal) {
            for($i = 0; $i < $request->jumAwal; $i++) {
                array_push($kodeBarang, $request->kodeDetil[$i]);
            }

            $hapus = DetilRAR::where('id_retur', $request->kodeRet)
                    ->whereNotIn('id_barang', $kodeBarang)->get();
            foreach($hapus as $i) {
                $stok = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'F')->first();
                $stok->{'stok'} -= $i->qty;
                $stok->save();
            }

            DetilRAR::where('id_retur', $request->kodeRet)->whereNotIn('id_barang', $kodeBarang)->delete();
            DetilRJ::where('id_retur', $request->kodeRet)->whereNotIn('id_barang', $kodeBarang)->delete();
        }

        $items = DetilRAR::where('id_retur', $request->kodeRet)->orderBy('tgl_retur')->get();
        $returJual = DetilRJ::where('id_retur', $request->kodeRet)->orderBy('tgl_kirim')->get();

        $j = 0;
        foreach($items as $i) {
            // if($j < $request->jumAwal) {
            
            $tglDetil = $request->tglDetil[$j];
            $tglDetil = $this->formatTanggal($tglDetil, 'Y-m-d');

            // if($items->where('id_barang', $request->kodeDetil[$j])->count() == 0) {
            if(($tglDetil != $i->tgl_retur) || ($request->qtyDetil[$j] != $i->qty) || ($request->diskonDetil[$j] != $i->diskon)) { 
                // $i->id_barang = $request->kodeDetil[$j];
                $i->tgl_retur = $tglDetil;
                $i->qty = $request->qtyDetil[$j];
                $i->harga = str_replace(".", "", $request->hargaDetil[$j]);
                $i->diskon = $request->diskonDetil[$j];
                $i->diskonRp = str_replace(".", "", $request->diskonRpDetil[$j]);
                $i->save();

                $stok = StokBarang::where('id_barang', $returJual[$j]->id_barang)
                                ->where('id_gudang', $gudang[0]->id)
                                ->where('status', 'F')->first();
                $stok->{'stok'} -= $returJual[$j]->qty_retur;
                $stok->save();

                // $returJual[$j]->id_barang = $request->kodeDetil[$j];
                $returJual[$j]->tgl_kirim = $tglDetil;
                $returJual[$j]->qty_retur = $request->qtyDetil[$j];
                $returJual[$j]->potong = $request->qtyDetil[$j];
                $returJual[$j]->save();

                $stok = StokBarang::where('id_barang', $request->kodeDetil[$j])
                                ->where('id_gudang', $gudang[0]->id)
                                ->where('status', 'F')->first();
                $stok->{'stok'} += $request->qtyDetil[$j];
                $stok->save();
            }

            $totalAwal += str_replace(".", "", $request->nettoDetil[$j]);
            $j++;
        } 

        $lastcodeKRM = DetilRJ::selectRaw('max(id_kirim) as id')->whereYear('created_at', $waktu->year)
                    ->whereMonth('created_at', $month)->get();;
        $lastnumberKRM = (int) substr($lastcodeKRM->first()->id, 7, 4);
        $lastnumberKRM++;
        $newcodeKRM = 'KRM'.$tahun.$bulan.sprintf("%04s", $lastnumberKRM);
        
        $total = 0;
        for($i = 0; $i < $jumBaris; $i++) {
            if(($request->{"kodeBarang".$request->kode}[$i] != '') && ($request->{"qty".$request->kode}[$i] != '')) {
                $tglRetur = $request->{"tglRetur".$request->kode}[$i];
                $tglRetur = $this->formatTanggal($tglRetur, 'Y-m-d');

                DetilRAR::create([
                    'id_retur' => $kodeAR,
                    'id_barang' => $request->{"kodeBarang".$request->kode}[$i],
                    'tgl_retur' => $tglRetur,
                    'qty' => $request->{"qty".$request->kode}[$i],
                    'harga' => str_replace(".", "", $request->{"harga".$request->kode}[$i]),
                    'diskon' => $request->{"diskon".$request->kode}[$i],
                    'diskonRp' => str_replace(".", "", $request->{"diskonRp".$request->kode}[$i]),
                ]);

                DetilRJ::create([
                    'id_retur' => $kodeAR,
                    'id_barang' => $request->{"kodeBarang".$request->kode}[$i],
                    'id_kirim' => $newcodeKRM,
                    'tgl_kirim' => $tglRetur,
                    'qty_retur' => $request->{"qty".$request->kode}[$i],
                    'qty_kirim' => 0,
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

        if($item->count() == 0) {
            $ret = AR_Retur::where('id', $kodeAR)->first();
            $total = $total;
        } else {
            $ret = AR_Retur::where('id', $item->first()->id)->first(); 
            $total += $totalAwal;
        }

        $ret->{'total'} = $total;
        $ret->save();

        return redirect()->route('ar');
    }

    public function cetak(Request $request, $status) {
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
                if(ucfirst($request->bulan) == $bulan[$i]) {
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

        if($status == 'All') {
            $items = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                    ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->whereBetween('tgl_so', [$awal, $akhir])->where('kategori', 'NOT LIKE', 'Extrana%')
                    ->where('kategori', 'NOT LIKE', 'Prime%')->orderBy('so.id_sales')->orderBy('customer.nama')->get();
                    // ->orderBy('so.id_sales')

            $itemsEx = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                    ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->whereBetween('tgl_so', [$awal, $akhir])->where('kategori', 'LIKE', 'Extrana%')
                    ->orderBy('so.id_sales')->orderBy('customer.nama')->get();
                    // ->orderBy('so.id_sales')
        } else {
            $items = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                    ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->whereBetween('tgl_so', [$awal, $akhir])->where('kategori', 'LIKE', 'Prime%')
                    ->orderBy('so.id_sales')->orderBy('customer.nama')->get();
                    // ->orderBy('so.id_sales')
            $itemsEx = NULL;
        }
        
        $data = [
            'items' => $items,
            'itemsEx' => $itemsEx,
            'awal' => $request->tglAwal,
            'akhir' => $request->tglAkhir, 
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.receivable.cetak', $data)
                ->setPaper('a4', 'landscape');
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    }

    public function cetakNow(Request $request, $status) {
        $tahun = Carbon::now('+07:00');
        $waktu = $tahun->format('d F Y, H:i:s');
        $tanggal = Carbon::now()->toDateString();

        if($status == 'All') {
            $items = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                    ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('tgl_so', $tanggal)->where('kategori', 'NOT LIKE', 'Extrana%')
                    ->where('kategori', 'NOT LIKE', 'Prime%')->orderBy('so.id_sales')->orderBy('customer.nama')->get();
                    // ->orderBy('so.id_sales')

            $itemsEx = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                    ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('tgl_so', $tanggal)->where('kategori', 'LIKE', 'Extrana%')
                    ->orderBy('so.id_sales')->orderBy('customer.nama')->get();
                    // ->orderBy('so.id_sales')

        } else {
            $items = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                    ->select('so.id as id', 'so.*')->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('tgl_so', $tanggal)->where('kategori', 'LIKE', 'Prime%')
                    ->orderBy('so.id_sales')->orderBy('customer.nama')->get();
                    // ->orderBy('so.id_sales')

            $itemsEx = NULL;
        }

        $tanggal = $this->formatTanggal($tanggal, 'd-M-y');
        
        $data = [
            'items' => $items,
            'itemsEx' => $itemsEx,
            'awal' => $tanggal,
            'akhir' => $tanggal, 
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.receivable.cetak', $data)
                ->setPaper('a4', 'landscape');
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    }

    public function excel(Request $request, $status) {
        $stat = $request->status;

        $tglAwal = $request->tglAwal;
        if(($request->tglAwal == '') && ($request->bulan == ''))
            $awal = '2015-01-01';
        else
            $awal = $this->formatTanggal($request->tglAwal, 'Y-m-d');

        $tglAkhir= $request->tglAkhir;
        if(($request->tglAwal == '') && ($request->bulan == ''))
            $akhir = Carbon::now()->toDateString();
        else
            $akhir = $this->formatTanggal($request->tglAkhir, 'Y-m-d');

        if($request->bulan == '')
            $bul = 'KOSONG';
        else
            $bul = ucfirst($request->bulan);

        if(($request->tglAwal == '') && ($request->bulan == ''))
            $tglAwal = '2015-01-01';
        elseif(($request->tglAwal == '') && ($request->bulan != ''))
            $tglAwal = 'KOSONG';
        else
            $tglAwal = $request->tglAwal;
        
        if(($request->tglAkhir == '') && ($request->bulan == ''))
            $tglAkhir = Carbon::now()->toDateString();
        elseif(($request->tglAkhir == '') && ($request->bulan != ''))
            $tglAkhir = 'KOSONG';
        else
            $tglAkhir = $request->tglAkhir;

        $fileAwal = $this->formatTanggal($request->tglAwal, 'd');
        $fileAkhir = $this->formatTanggal($request->tglAkhir, 'd M');

        if($request->bulan == '')
            $tglFile = $fileAwal.'-'.$fileAkhir;
        else
            $tglFile = ucfirst($request->bulan);

        return Excel::download(new TransAllExport($tglAwal, $tglAkhir, $awal, $akhir, $bul, $status, $stat), 'TH-'.$status.'-'.$tglFile.'.xlsx');
    }

    public function excelNow($status) {
        $tanggal = Carbon::now()->toDateString();
        $tanggalStr = $this->formatTanggal($tanggal, 'd-M-y');
        $tglFile = $this->formatTanggal($tanggal, 'd-M');

        return Excel::download(new TransHarianExport($tanggal, $tanggalStr, $status), 'TH-'.$status.'-'.$tglFile.'.xlsx');
    }
}
