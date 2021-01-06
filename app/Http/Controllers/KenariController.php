<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\Harga;
use App\Models\HargaBarang;
use App\Models\Customer;
use App\Models\Gudang;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\AR_Retur;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

class KenariController extends Controller
{
    public function index() {
        $items = Barang::All();
        $harga = Harga::All();
        $hargaBarang = HargaBarang::All();

        $data = [
            'items' => $items,
            'harga' => $harga,
            'hargaBarang' => $hargaBarang
        ];

        return view('pages.kenari.stok.index', $data);
    }

    public function so($status) {
        $customer = Customer::with(['sales'])->get();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $hrg = Harga::All();
        $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                ->where('tipe', 'KENARI')->get();
        $gudang = Gudang::where('tipe', 'KENARI')->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = SalesOrder::selectRaw('max(id) as id')->whereMonth('tgl_so', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 4);
        $lastnumber++;
        $newcode = 'INV'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        $lastcodeKen = SalesOrder::join('users', 'users.id', 'so.id_user')
                        ->selectRaw('max(so.id) as id')->where('roles', 'KENARI')
                        ->whereMonth('tgl_so', $month)->get();

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
            'barang' => $barang,
            'harga' => $harga,
            'hrg' => $hrg,
            'stok' => $stok,
            'gudang' => $gudang,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'status' => $status,
            'lastcode' => $lastcodeKen,
            'totalKredit' => $totalPerCust
        ];

        return view('pages.kenari.so.index', $data);
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

        $lastcode = SalesOrder::selectRaw('max(id) as id')->whereMonth('tgl_so', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 4);
        $lastnumber++;
        $newcode = 'INV'.$tahun.$bulan.sprintf('%04s', $lastnumber);
        $kode = $newcode;
        
        SalesOrder::create([
            'id' => $kode,
            'tgl_so' => $tanggal,
            'tgl_kirim' => $tglKirim,
            'total' => str_replace(".", "", $request->grandtotal),
            'diskon' => str_replace(".", "", $diskon),
            'kategori' => $request->kategori,
            'tempo' => $tempo,
            'pkp' => $pkp,
            'status' => 'INPUT',
            'id_customer' => $request->kodeCustomer,
            'id_user' => Auth::user()->id
        ]);

        $lastcode = AccReceivable::max('id');
        $lastnumber = (int) substr($lastcode, 2, 4);
        $lastnumber++;
        $newcode = 'AR'.sprintf('%04s', $lastnumber);

        if($status != 'LIMIT') {
            AccReceivable::create([
                'id' => $newcode,
                'id_so' => $id,
                'keterangan' => 'BELUM LUNAS'
            ]);
        }

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        if($status == 'LIMIT') {
            NeedApproval::create([
                'id' => $newcode,
                'tanggal' => Carbon::now()->toDateString(),
                'status' => 'LIMIT',
                'keterangan' => 'Melebihi limit',
                'id_dokumen' => $id,
                'tipe' => 'Faktur'
            ]);
        }

        for($i = 0; $i < $jumlah; $i++) {
            if(($request->kodeBarang[$i] != "") && ($request->qty[$i] != "")) {
                DetilSO::create([
                    'id_so' => $kode,
                    'id_barang' => $request->kodeBarang[$i],
                    'id_gudang' => $request->kodeGudang[$i],
                    'harga' => str_replace(".", "", $request->harga[$i]),
                    'qty' => $request->qty[$i],
                    'diskon' => $request->diskon[$i],
                    'diskonRp' =>  str_replace(".", "", $request->diskonRp[$i])
                ]);

                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                            ->where('id_gudang', $request->kodeGudang[$i])->first();
                $updateStok->{'stok'} -= $request->qty[$i];
                $updateStok->save();
            }
        }

        if($status != 'CETAK')
            $cetak = 'false';
        else {
            $cetak = 'true';
        }

        return redirect()->route('so-kenari', $cetak);
    }

    public function cetak(Request $request, $id) {
        $items = SalesOrder::with(['customer'])->where('id', $id)->get();
        $today = Carbon::now()->isoFormat('dddd, D MMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.kenari.so.cetakPdf', $data);
    }
    
    public function afterPrint($id) {
        $item = SalesOrder::where('id', $id)->first();
        $item->{'status'} = 'CETAK';
        $item->save();

        $data = [
            'status' => 'false'
        ];

        return redirect()->route('so-kenari', $data);
    }

    public function change() {
        $so = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', 'KENARI')->get();
        $customer = Customer::All();

        $data = [
            'so' => $so,
            'customer' => $customer
        ];

        return view('pages.kenari.ubahfaktur.index', $data);
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
                    ->select('so.id as id', 'so.*')->where('roles', 'KENARI')
                    ->where(function($q) use ($id, $kode, $tglAwal, $tglAkhir) {
                        $q->where('so.id', $id)
                        ->orWhere('id_customer', $kode)
                        ->orWhereBetween('tgl_so', [$tglAwal, $tglAkhir]);
                    })->orderBy('so.id', 'asc')->get();
        } else {
            $items = SalesOrder::with(['customer', 'need_approval'])
                    ->join('users', 'users.id', 'so.id_user')
                    ->select('so.id as id', 'so.*')->where('roles', 'KENARI')
                    ->where(function($q) use ($id, $kode, $tglAwal, $tglAkhir) {
                        $q->where('id_customer', $kode)
                        ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                        ->orWhere('so.id', $id);
                    })->orderBy('so.id', 'asc')->get();
        }

        // return response()->json($items);
        
        $customer = Customer::All();
        $stok = StokBarang::All();
        $so = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', 'KENARI')->get();
        
        $data = [
            'items' => $items,
            'customer' => $customer,
            'stok' => $stok,
            'so' => $so,
            'id' => $request->id,
            'nama' => $request->nama,
            'kode' => $request->kode,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.kenari.ubahfaktur.detail', $data);
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
            'tipe' => 'Faktur'
        ]);

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
        $itemsRow = DetilSO::where('id_so', $id)->distinct('id_barang')->count();
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-M-y');
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $hrg = Harga::All();
        $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                ->where('tipe', 'KENARI')->get();
        $gudang = Gudang::where('tipe', 'KENARI')->get();

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'hrg' => $hrg,
            'stok' => $stok,
            'gudang' => $gudang,
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.kenari.ubahfaktur.edit', $data);
    }

    public function update(Request $request) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        $items = SalesOrder::with(['customer', 'need_approval'])
                ->where('id', $request->kode)->get();
        if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE'))
            $kode = $items[0]->need_approval->last()->id;

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now(),
            'status' => 'PENDING_UPDATE',
            'keterangan' => $request->keterangan,
            'id_dokumen' => $request->kode,
            'tipe' => 'Faktur'
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            NeedAppDetil::create([
                'id_app' => $newcode,
                'id_barang' => $request->kodeBarang[$i],
                'id_gudang' => $request->kodeGudang[$i],
                'harga' => str_replace(".", "", $request->harga[$i]),
                'qty' => $request->qty[$i],
                'diskon' => $request->diskon[$i],
                'diskonRp' => str_replace(".", "", $request->diskonRp[$i])
            ]);

            if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                $stokAwal = NeedAppDetil::where('id_app', $kode)
                                ->where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $request->kodeGudang[$i])->first();
            } else {
                $stokAwal = DetilSO::where('id_so', $request->kode)
                        ->where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $request->kodeGudang[$i])->first();
            }
            
            $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $request->kodeGudang[$i])->first();

            if($stokAwal != NULL) {
                if($stokAwal->{'qty'} > $request->qty[$i])
                    $updateStok->{'stok'} += ($stokAwal->{'qty'} - $request->qty[$i]);
                else 
                    $updateStok->{'stok'} -= ($request->qty[$i] - $stokAwal->{'qty'});
            } else {
                $updateStok->{'stok'} -= $request->qty[$i];
            }
            
            $updateStok->save();
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
            for($i = 0; $i < $items->count(); $i++) {
                if($items[$i]->id_barang != $detil[$i]->id_barang) {
                    $updateStok = StokBarang::where('id_barang', $items[$i]->id_barang)
                                ->where('id_gudang', $items[$i]->id_gudang)->first();
                    $updateStok->{'stok'} += $items[$i]->qty;
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

        $url = Route('so-show-kenari', $data);
        return redirect($url);
    }

    public function indexCetak($status, $awal, $akhir) {
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.*')->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                ->where('roles', 'KENARI')->get();

        $data = [
            'items' => $items,
            'status' => $status,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.kenari.cetakfaktur.index', $data);
    }

    public function processFaktur(Request $request) {
        $data = [
            'awal' => $request->kodeAwal,
            'akhir' => $request->kodeAkhir,
            'status' => 'true'
        ];

        return redirect()->route('cetak-faktur-kenari', $data);
    }

    public function cetakFaktur($awal, $akhir) {
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.*')->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                ->whereBetween('so.id', [$awal, $akhir])->where('roles', 'KENARI')->get();

        $today = Carbon::now()->isoFormat('dddd, D MMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.kenari.cetakfaktur.cetakPdf', $data);
    } 

    public function updateFaktur($awal, $akhir) {
        $items = SalesOrder::whereBetween('id', [$awal, $akhir])->get();

        foreach($items as $item) {
            $item->status = 'CETAK';
            $item->save();
        }

        $data = [
            'status' => 'false',
            'awal' => 0,
            'akhir' => 0
        ];

        return redirect()->route('cetak-faktur-kenari', $data);
    }

    public function indexTrans() {
        $tanggal = Carbon::now()->toDateString();

        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', 'KENARI')
                ->where('tgl_so', $tanggal)->get(); 

        $data = [
            'items' => $items
        ];

        return view('pages.kenari.transaksiharian.index', $data);
    }

    public function showTrans(Request $request) {
        $tglAwal = $request->tglAwal;
        $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
        $tglAkhir = $request->tglAkhir;
        $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');
        
        $items = SalesOrder::with('customer')
                ->join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', 'KENARI')
                ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'asc')->get();
        
        $data = [
            'items' => $items,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.transaksiharian.show', $data);
    }
}
