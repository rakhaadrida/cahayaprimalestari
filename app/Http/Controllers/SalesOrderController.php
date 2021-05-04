<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\DetilSO;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use App\Models\HargaBarang;
use App\Models\Harga;
use App\Models\Gudang;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\AR_Retur;
use App\Models\TandaTerima;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// use PDF;

class SalesOrderController extends Controller
{
    public function index($status) {
        $customer = Customer::with(['sales'])->get();
        $cust = Customer::pluck('nama')->toArray();
        $sales = Sales::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        // $hrg = Harga::All();
        $hrg = Harga::pluck('tipe')->toArray();
        $kodeBarang = Barang::pluck('id')->toArray();
        $namaBarang = Barang::pluck('nama')->toArray();
        $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                ->where('tipe', 'BIASA')->get();
        $gudang = Gudang::where('tipe', 'BIASA')->get();
        $lastSO = SalesOrder::where('created_at', '!=', NULL)->latest()->take(1)->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = SalesOrder::selectRaw('max(id) as id')->where('id', 'LIKE', 'IV%')
                    ->whereYear('tgl_so', $waktu->year)
                    ->whereMonth('tgl_so', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'IV'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $cicilPerCust = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(cicil) as totCicil'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $totalPerCust = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(total) as totKredit'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')->get();

        $returPerCust = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(ar_retur.total) as totRetur'))->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')->get();

        foreach($totalPerCust as $q) {
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] = $q->totKredit - $h->totCicil;
                } else {
                    $q['total'] = $q->totKredit - 0;
                }
            }

            foreach($returPerCust as $r) {
                if($q->id_customer == $r->id_customer) {
                    $q['total'] -= $r->totRetur;
                } else {
                    $q['total'] -= 0;
                }
            }
        }

        $data = [
            'customer' => $customer,
            'cust' => $cust,
            'sales' => $sales,
            'barang' => $barang,
            'kodeBarang' => $kodeBarang,
            'namaBarang' => $namaBarang,
            'harga' => $harga,
            'hrg' => $hrg,
            'stok' => $stok,
            'gudang' => $gudang,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'status' => $status,
            'lastcode' => $lastcode,
            'lastSO' => $lastSO,
            'totalKredit' => $totalPerCust
        ];

        return view('pages.penjualan.so.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function process(Request $request, $id, $status) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $tglKirim = $request->tanggalKirim;
        $tglKirim = $this->formatTanggal($tglKirim, 'Y-m-d');
        $jumlah = $request->jumBaris;

        if($request->tempo == "") 
            $tempo = 0;
        else
            $tempo = $request->tempo;

        if($request->pkp == "") 
            $pkp = 0;
        else
            $pkp = $request->pkp;

        if($request->diskonFaktur == "") 
            $diskon = 0;
        else
            $diskon = $request->diskonFaktur;

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = SalesOrder::selectRaw('max(id) as id')->where('id', 'LIKE', 'IV%')
                    ->whereYear('tgl_so', $waktu->year)
                    ->whereMonth('tgl_so', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'IV'.$tahun.$bulan.sprintf('%04s', $lastnumber);
        $kode = $newcode;

        $statusHal = $status;

        if($status != 'LIMIT')
            $status = 'INPUT';

        $totNetto = 0;
        for($i = 0; $i < $jumlah; $i++) {
            if(($request->kodeBarang[$i] != "") && ($request->qty[$i] != "")) {
                $totNetto += str_replace(".", "", $request->netto[$i]);
            }
        }
        
        SalesOrder::create([
            'id' => $kode,
            // 'id' => $request->kode,
            'tgl_so' => $tanggal,
            'tgl_kirim' => $tglKirim,
            // 'total' => str_replace(".", "", $request->grandtotal),
            'total' => $totNetto,
            'diskon' => str_replace(".", "", $diskon),
            'kategori' => $request->kategori.' '.$request->jenis,
            'tempo' => $tempo,
            'pkp' => $pkp,
            'status' => $status,
            'id_customer' => $request->kodeCustomer,
            'id_sales' => $request->kodeSales,
            'id_user' => Auth::user()->id
        ]);

        $lastcode = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->selectRaw('max(ar.id) as id')->where('ar.id', 'LIKE', '%'.$tahun.$bulan.'%')->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'AR'.$tahun.$bulan.sprintf('%04s', $lastnumber);
        $arKode = $newcode;

        if($status != 'LIMIT') {
            AccReceivable::create([
                'id' => $newcode,
                'id_so' => $kode,
                'keterangan' => ($request->namaCustomer != 'REVISI' ? 'BELUM LUNAS' : 'LUNAS')
            ]);
        }

        $lastcode = DetilAR::selectRaw('max(id_cicil) as id')->whereYear('tgl_bayar', $waktu->year)
                    ->whereMonth('tgl_bayar', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'CIC'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        if($request->namaCustomer == 'REVISI') {
            DetilAR::create([
                'id_ar' => $arKode,
                'id_cicil' => $newcode,
                'tgl_bayar' => Carbon::now()->toDateString(),
                'cicil' => 0
            ]);
        }

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        if($status == 'LIMIT') {
            NeedApproval::create([
                'id' => $newcode,
                'tanggal' => Carbon::now('+07:00'),
                'status' => 'LIMIT',
                'keterangan' => 'Melebihi limit',
                'id_dokumen' => $kode,
                'tipe' => 'Faktur',
                'id_user' => Auth::user()->id
            ]);
        }

        $totNetto = 0;
        for($i = 0; $i < $jumlah; $i++) {
            if(($request->kodeBarang[$i] != "") && ($request->qty[$i] != "")) {
                $arrGudang = explode(",", $request->kodeGudang[$i]);
                $arrStok = explode(",", $request->qtyGudang[$i]);
                $diskonRp = ($request->diskonRp[$i] != '' ? str_replace(".", "", $request->diskonRp[$i]) : 0) / sizeof($arrGudang);

                for($j = 0; $j < sizeof($arrGudang); $j++) {
                    DetilSO::create([
                        'id_so' => $kode,
                        // 'id_so' => $request->kode,
                        'id_barang' => $request->kodeBarang[$i],
                        'id_gudang' => $arrGudang[$j],
                        'harga' => str_replace(".", "", $request->harga[$i]),
                        'qty' => $arrStok[$j],
                        'diskon' => ($request->diskon[$i] != '' ? $request->diskon[$i] : '0'),
                        // 'diskonRp' => $diskonRp
                        'diskonRp' => ($request->diskonRp[$i] != '' ? ($j == 0 ? str_replace(".", "", $request->diskonRp[$i]) : 0) : 0)
                    ]);

                    $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $arrGudang[$j])->first();
                    $updateStok->{'stok'} -= $arrStok[$j];
                    $updateStok->save();
                    
                    /* foreach($updateStok as $us) {
                        if($request->qty[$i] <= $us->stok) {
                            $us->stok -= $request->qty[$i];
                        }
                        else {
                            $qty -= $us->stok;
                            $us->stok -= $us->stok;
                        }
                        $us->save();
                    } */
                }

                // $totNetto += str_replace(".", "", $request->netto[$i]);
            }
        }

        if($statusHal != 'CETAK')
            $cetak = 'false';
        else {
            $cetak = 'true';
        }

        if($statusHal != 'CETAK')
            return redirect()->route('so', 'false');
        else
            return redirect()->route('so-cetak', $kode);
        // return redirect()->route('so', $cetak);
    }

    public function cetak(Request $request, $id) {
        $items = SalesOrder::where('id', $id)->get();
        $tabel = ceil($items->first()->detilso->count() / 12);

        if($tabel > 1) {
            for($i = 1; $i < $tabel; $i++) {
                $item = collect([
                    'id' => $items->first()->id,
                    'tgl_so' => $items->first()->tgl_so,
                    'tgl_kirim' => $items->first()->tgl_kirim,
                    'total' => $items->first()->total,
                    'diskon' => $items->first()->diskon,
                    'kategori' => $items->first()->kategori,
                    'tempo' => $items->first()->tempo,
                    'pkp' => $items->first()->pkp,
                    'status' => $items->first()->status,
                    'id_customer' => $items->first()->id_customer,
                    'id_sales' => $items->first()->id_sales,
                    'id_user' => $items->first()->id_user,
                ]);

                $items->push($item);
            }
        }

        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu,
            'id' => $id
        ];

        return view('pages.penjualan.so.cetakInv', $data);
    }

    public function tandaterima($id) {
        $items = SalesOrder::where('id', $id)->get();

        $lastcode = TandaTerima::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TTR'.sprintf('%04s', $lastnumber);

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'newcode' => $newcode,
            'today' => $today,
            'waktu' => $waktu
        ];

        $paper = array(0,0,612,394);
        $pdf = PDF::loadview('pages.penjualan.tandaterima.cetak', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-ttr.pdf');
    }

    public function afterPrint($id) {
        $item = SalesOrder::where('id', $id)->first();
        $item->{'status'} = 'CETAK';
        $item->save();

        $data = [
            'status' => 'false'
        ];

        return redirect()->route('so', $data);
    }

    public function change() {
        $so = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('tgl_so', '>', Carbon::now()->subMonths(1))
                ->where('roles', '!=', 'KENARI')->orderBy('created_at', 'desc')->get();
        $customer = Customer::All();

        $data = [
            'so' => $so,
            'customer' => $customer
        ];

        return view('pages.penjualan.ubahfaktur.index', $data);
    }

    public function show(Request $request) {
        $id = $request->id;
        $kode = $request->kode;
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        if(($tglAwal != NULL) && ($tglAkhir != NULL)) {
            $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
            $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');
        }

        $isi = 1;
        if(($request->kode != '') && ($request->tglAwal != '') && ($request->tglAkhir != ''))
            $isi = 2;

        if($isi == 1) {
            $items = SalesOrder::with(['customer', 'need_approval'])
                    ->join('users', 'users.id', 'so.id_user')
                    ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')
                    ->where(function($q) use ($id, $kode, $tglAwal, $tglAkhir) {
                        $q->where('so.id', $id)
                        ->orWhere('id_customer', $kode)
                        ->orWhereBetween('tgl_so', [$tglAwal, $tglAkhir]);
                    })->orderBy('tgl_so', 'asc')->orderBy('so.id', 'asc')->get();
        } else {
            $items = SalesOrder::with(['customer', 'need_approval'])
                    ->join('users', 'users.id', 'so.id_user')
                    ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')
                    ->where(function($q) use ($id, $kode, $tglAwal, $tglAkhir) {
                        $q->where('id_customer', $kode)
                        ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                        ->orWhere('so.id', $id);
                    })->orderBy('tgl_so', 'asc')->orderBy('so.id', 'asc')->get();
        }
        
        $customer = Customer::All();
        $gudang = Gudang::where('tipe', 'BIASA')->get();
        $stok = StokBarang::All();
        $so = SalesOrder::join('users', 'users.id', 'so.id_user')
                        ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')->get();
        
        $data = [
            'items' => $items,
            'customer' => $customer,
            'gudang' => $gudang,
            'stok' => $stok,
            'so' => $so,
            'id' => $request->id,
            'nama' => $request->nama,
            'kode' => $request->kode,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.ubahfaktur.detail', $data);
    }

    public function status(Request $request, $id) {
        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        $items = NeedApproval::with(['need_appdetil'])->where('id_dokumen', $id)
                ->orderBy('created_at', 'desc')->get();

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'PENDING_BATAL',
            'keterangan' => $request->input("ket".$id),
            'id_dokumen' => $id,
            'tipe' => 'Faktur',
            'id_user' => Auth::user()->id
        ]);
   
        // return response()->json($items);

        if(($items->count() != 0) && ($items->first()->need_appdetil->count() != 0)) {
            $detil = NeedAppDetil::where('id_app', $items[0]->need_appdetil[0]->id_app)->get();
        } else {
            $detil = DetilSO::with(['so'])->where('id_so', $id)->get();
        }

        foreach($detil as $item) {
            $updateStok = StokBarang::where('id_barang', $item->id_barang)
                    ->where('id_gudang', $item->id_gudang)->first();

            $updateStok->{'stok'} += $item->qty;
            $updateStok->save();
        }

        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');  
    }

    public function edit(Request $request, $id) {
        $items = SalesOrder::with(['customer', 'need_approval'])->where('id', $id)->get();
        $itemsRow = DetilSO::where('id_so', $id)->groupBy('id_barang')->get();
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-M-y');
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $hrg = Harga::All();
        $sales = Sales::All();
        $customer = Customer::All();
        $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                ->where('tipe', 'BIASA')->get();
        $gudang = Gudang::where('tipe', 'BIASA')->get();
        // return response()->json($id);

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'hrg' => $hrg,
            'sales' => $sales,
            'customer' => $customer,
            'gudang' => $gudang,
            'stok' => $stok,
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.ubahfaktur.edit', $data);
    }

    public function update(Request $request) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        $tgl_so = $request->tglSO;
        $tgl_so = $this->formatTanggal($tgl_so, 'Y-m-d');

        $items = SalesOrder::with(['customer', 'need_approval'])->where('id', $request->kode)->get();
        $items->first()->tgl_so = $tgl_so;
        $items->first()->id_customer = $request->kodeCust;
        $items->first()->id_sales = $request->kodeSales;
        $items->first()->kategori = $request->kategori;
        $items->first()->tempo = $request->tempo;
        $items->first()->save();

        if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE'))
            $kode = $items[0]->need_approval->last()->id;

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now('+07:00'),
            'status' => 'PENDING_UPDATE',
            'keterangan' => $request->keterangan,
            'id_dokumen' => $request->kode,
            'tipe' => 'Faktur',
            'id_user' => Auth::user()->id
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            $arrGudang = explode(",", $request->kodeGudang[$i]);
            $arrStok = explode(",", $request->qtyGudang[$i]);
            $diskonRp = str_replace(".", "", $request->diskonRp[$i]) / sizeof($arrGudang);

            for($j = 0; $j < sizeof($arrGudang); $j++) {
                NeedAppDetil::create([
                    'id_app' => $newcode,
                    'id_barang' => $request->kodeBarang[$i],
                    'id_gudang' => $arrGudang[$j],
                    'harga' => str_replace(".", "", $request->harga[$i]),
                    'qty' => $arrStok[$j],
                    'diskon' => $request->diskon[$i],
                    // 'diskonRp' => $diskonRp
                    'diskonRp' => ($request->diskonRp[$i] != '' ? ($j == 0 ? str_replace(".", "", $request->diskonRp[$i]) : 0) : 0)
                ]);

                if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                    $stokAwal = NeedAppDetil::where('id_app', $kode)
                                    ->where('id_barang', $request->kodeBarang[$i])
                                    ->where('id_gudang', $arrGudang[$j])->first();
                } else {
                    $stokAwal = DetilSO::where('id_so', $request->kode)
                            ->where('id_barang', $request->kodeBarang[$i])
                            ->where('id_gudang', $arrGudang[$j])->first();
                }
                
                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                            ->where('id_gudang', $arrGudang[$j])->first();

                if($stokAwal != NULL) {
                    if($stokAwal->{'qty'} > $arrStok[$j])
                        $updateStok->{'stok'} += ($stokAwal->{'qty'} - $arrStok[$j]);
                    else 
                        $updateStok->{'stok'} -= ($arrStok[$j] - $stokAwal->{'qty'});
                } else {
                    $updateStok->{'stok'} -= $arrStok[$j];
                }
                
                $updateStok->save();
            }
        }

        $items = SalesOrder::with(['customer', 'need_approval'])
                ->where('id', $request->kode)->get();
        
        if(($items[0]->need_approval->count() > 1) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
            $itemsApp = NeedApproval::where('id_dokumen', $request->kode)
                        ->latest()->skip(1)->take(1)->get();
            $items = $itemsApp->last()->need_appdetil;

            $detilApp = NeedApproval::where('id_dokumen', $request->kode)->latest()->get();
            $detil = $detilApp->first()->need_appdetil;
        } else { 
            $items = DetilSO::where('id_so', $request->kode)->get();
            $detil = NeedAppDetil::where('id_app', $newcode)->get();
        }

        if($items->count() != $detil->count()) {
            foreach($items as $item) {
                $cek = 0;
                foreach($detil as $d) {
                    if($item->id_barang == $d->id_barang) {
                        $cek = 1; 
                        break;
                    }
                }

                if($cek == 0) {
                    $updateStok = StokBarang::where('id_barang', $item->id_barang)
                        ->where('id_gudang', $item->id_gudang)->first();
                    $updateStok->{'stok'} += $item->qty;
                    $updateStok->save();
                }
            }
        } else {
            foreach($items as $item) {
                $cek = 0;
                foreach($detil as $d) {
                    if($item->id_barang == $d->id_barang) {
                        $cek = 1; 
                        break;
                    }
                }

                if($cek == 0) {
                    $updateStok = StokBarang::where('id_barang', $item->id_barang)
                        ->where('id_gudang', $item->id_gudang)->first();
                    $updateStok->{'stok'} += $item->qty;
                    $updateStok->save();
                }
            }
        }

        $data = [
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        $url = Route('so-show', $data);
        return redirect($url);
    }

    /* 
        $tempDetil = TempDetilSO::where('id_so', $id)->get();
        foreach($tempDetil as $td) {
            DetilSO::create([
                'id_so' => $td->id_so,
                'id_barang' => $td->id_barang,
                'harga' => $td->harga,
                'qty' => $td->qty,
                'diskon' => $td->diskon
            ]);

            // $updateStok = StokBarang::where('id_barang', $td->id_barang)
            //                 ->where('id_gudang', 'GDG01')->first();

            $deleteTemp = TempDetilSO::where('id_so', $id)->where('id_barang', $td->id_barang)->delete();
        } */
}
