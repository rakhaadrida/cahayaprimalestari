<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\HargaBarang;
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\AccPayable;
use App\Models\DetilAP;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class BarangMasukController extends Controller
{
    public function index($status) {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $gudang = Gudang::where('tipe', '!=', 'RETUR')->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        // autonumber
        $lastcode = BarangMasuk::selectRaw('max(id) as id')->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'BM'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        // date now
        $tanggal = Carbon::now('+07:00')->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'supplier' => $supplier,
            'newcode' => $newcode,
            'lastcode' => $lastcode,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'gudang' => $gudang,
            'status' => $status
        ];

        return view('pages.pembelian.barangmasuk.index', $data);
        // return view('pages.pembelian.barangmasuk.indexAlter', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function process (Request $request, $id, $status) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = BarangMasuk::selectRaw('max(id) as id')->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcodeBM = 'BM'.$tahun.$bulan.sprintf('%04s', $lastnumber);
        $kodeBM = $newcodeBM;

        BarangMasuk::create([
            'id' => $newcodeBM,
            'id_faktur' => $request->kode,
            'tanggal' => $tanggal,
            'total' => str_replace(".", "", $request->subtotal),
            'potongan' => 0,
            'id_gudang' => $request->kodeGudang,
            'id_supplier' => $request->kodeSupplier,
            'tempo' => $request->tempo != '' ? $request->tempo : 0,
            'status' => 'INPUT',
            'diskon' => 'F',
            'id_user' => Auth::user()->id
        ]);

        $lastcode = AccPayable::join('barangmasuk', 'barangmasuk.id_faktur', 'ap.id_bm')
                    ->selectRaw('max(ap.id) as id')->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'AP'.$tahun.$bulan.sprintf('%04s', $lastnumber);
        $apKode = $newcode;

        $items = AccPayable::where('id_bm', $request->kode)->count();
        if($items != 1) {
            AccPayable::create([
                'id' => $newcode,
                'id_bm' => $request->kode,
                'keterangan' => ($request->namaSupplier != 'REVISI' ? 'BELUM LUNAS' : 'LUNAS')
            ]);
        }

        $lastcode = DetilAP::selectRaw('max(id_bayar) as id')->whereYear('tgl_bayar', $waktu->year)
                    ->whereMonth('tgl_bayar', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'TRS'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        if($request->namaSupplier == 'REVISI') {
            DetilAP::create([
                'id_ap' => $apKode,
                'id_bayar' => $newcode,
                'tgl_bayar' => Carbon::now()->toDateString(),
                'transfer' => str_replace(".", "", $request->subtotal)
            ]);
        }

        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                DetilBM::create([
                    'id_bm' => $newcodeBM,
                    'id_barang' => $request->kodeBarang[$i],
                    'harga' => str_replace(".", "", $request->harga[$i]),
                    'qty' => $request->qty[$i],
                    'diskon' => NULL,
                    'disPersen' => NULL
                ]);

                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                            ->where('id_gudang', $request->kodeGudang)->first();
                if($updateStok == NULL) {
                    StokBarang::create([
                        'id_barang' => $request->kodeBarang[$i],
                        'id_gudang' => $request->kodeGudang,
                        'status' => 'T',
                        'stok' => $request->qty[$i]
                    ]);
                } else {
                    $updateStok->{'stok'} = $updateStok->{'stok'} + $request->qty[$i];
                    $updateStok->save();
                }
            }
        }

        if($status != 'CETAK')
            $cetak = 'false';
        else
            $cetak = 'true';

        if($status != 'CETAK')
            return redirect()->route('barangMasuk', 'false');
        else
            return redirect()->route('bm-cetak', $newcodeBM);
    }

    public function cetak(Request $request, $id) {
        $items = BarangMasuk::where('id', $id)->get();
        $tabel = ceil($items->first()->detilbm->count() / 34);

        if($tabel > 1) {
            for($i = 1; $i < $tabel; $i++) {
                $item = collect([
                    'id' => $items->first()->id,
                    'id_faktur' => $items->first()->id_faktur,
                    'tanggal' => $items->first()->tanggal,
                    'total' => $items->first()->total,
                    'id_supplier' => $items->first()->id_supplier,
                    'id_user' => $items->first()->id_user,
                ]);

                $items->push($item);
            }
        }
        $items = $items->values();

        $today = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now('+07:00');
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu,
            'id' => $id
        ];

        return view('pages.pembelian.barangmasuk.cetakPdf', $data);
    }

    public function afterPrint($id) {
        $item = BarangMasuk::where('id', $id)->first();
        $item->{'status'} = 'CETAK';
        $item->save();

        $data = [
            'status' => 'false'
        ];

        return redirect()->route('barangMasuk', $data);
    }

    public function change() {
        $bm = BarangMasuk::orderBy('tanggal', 'desc')->get();
        $supplier = Supplier::All();

        $data = [
            'bm' => $bm,
            'supplier' => $supplier
        ];

        return view('pages.pembelian.ubahBM.index', $data);
    }

    public function show(Request $request) {
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
            $items = BarangMasuk::with(['supplier', 'gudang', 'need_approval'])
                    ->where('id_faktur', $request->id)
                    ->orWhere('id_supplier', $request->kode)
                    ->orWhereBetween('tanggal', [$tglAwal, $tglAkhir])
                    ->orderBy('id', 'asc')->get();
        } else {
            $items = BarangMasuk::with(['supplier', 'gudang', 'need_approval'])
                    ->where('id_supplier', $request->kode)
                    ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                    ->orWhere('id', $request->id)
                    ->orderBy('id', 'asc')->get();
        }

        $supplier = Supplier::All();
        $stok = StokBarang::All();
        $bm = BarangMasuk::orderBy('tanggal', 'desc')->get();

        $data = [
            'items' => $items,
            'supplier' => $supplier,
            'stok' => $stok,
            'bm' => $bm,
            'id' => $request->id,
            'nama' => $request->nama,
            'kode' => $request->kode,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.pembelian.ubahBM.detail', $data);
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
            'tipe' => 'Dokumen',
            'id_user' => Auth::user()->id
        ]);

        if(($items->count() != 0) && ($items->first()->need_appdetil->count() != 0)) {
            $detil = NeedAppDetil::where('id_app', $items[0]->need_appdetil[0]->id_app)->get();
            $gudang = $detil[0]->id_gudang;
        } else {
            $detil = DetilBM::with(['bm'])->where('id_bm', $id)->get();
            $gudang = $detil[0]->bm->id_gudang;
        }

        foreach($detil as $item) {
            $updateStok = StokBarang::where('id_barang', $item->id_barang)
                    ->where('id_gudang', $gudang)->first();

            $updateStok->{'stok'} -= $item->qty;
            $updateStok->save();
        }

        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');
    }

    public function edit(Request $request, $id) {
        // $items = DetilBM::with(['bm', 'barang'])->where('id_bm', $id)->get();
        $items = BarangMasuk::with(['supplier', 'gudang'])->where('id', $id)
                ->get();
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $data = [
            'items' => $items,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.pembelian.ubahBM.edit', $data);
    }

    public function update(Request $request) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        $items = BarangMasuk::with(['supplier', 'gudang', 'need_approval'])
                ->where('id', $request->kode)->get();
        // return response()->json($items[0]->detilbm);

        if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE'))
            $kode = $items[0]->need_approval->last()->id;

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now(),
            'status' => 'PENDING_UPDATE',
            'keterangan' => $request->keterangan,
            'id_dokumen' => $request->kode,
            'tipe' => 'Dokumen',
            'id_user' => Auth::user()->id
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            NeedAppDetil::create([
                'id_app' => $newcode,
                'id_barang' => $request->kodeBarang[$i],
                'id_gudang' => $request->kodeGudang,
                'harga' => str_replace(".", "", $request->harga[$i]),
                'qty' => $request->qty[$i],
                'diskon' => $request->diskon[$i],
            ]);

            if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                $stokAwal = NeedAppDetil::where('id_app', $kode)
                            ->where('id_barang', $request->kodeBarang[$i])
                            ->where('id_gudang', $request->kodeGudang)->first();
            } else {
                $stokAwal = DetilBM::where('id_bm', $request->kode)
                            ->where('id_barang', $request->kodeBarang[$i])->first();
            }

            // return response()->json($stokAwal);
            $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $request->kodeGudang)->first();

            if($stokAwal != NULL) {
                if($stokAwal->{'qty'} > $request->qty[$i])
                    $updateStok->{'stok'} -= ($stokAwal->{'qty'} - $request->qty[$i]);
                else
                    $updateStok->{'stok'} += ($request->qty[$i] - $stokAwal->{'qty'});
            } else {
                $updateStok->{'stok'} += $request->qty[$i];
            }

            $updateStok->save();

        }

        $items = BarangMasuk::with(['supplier', 'gudang', 'need_approval'])
                ->where('id', $request->kode)->get();

        if(($items[0]->need_approval->count() > 1) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
            $itemsApp = NeedApproval::where('id_dokumen', $request->kode)
                        ->latest()->skip(1)->take(1)->get();
            $items = $itemsApp->last()->need_appdetil;

            $detilApp = NeedApproval::where('id_dokumen', $request->kode)->latest()->get();
            $detil = $detilApp->first()->need_appdetil;
            // return response()->json($detil);
        } else {
            $items = DetilBM::where('id_bm', $request->kode)->get();
            $detil = NeedAppDetil::where('id_app', $newcode)->get();
        }

        // return response()->json($items);

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
                        ->where('id_gudang', $request->kodeGudang)->first();
                    $updateStok->{'stok'} -= $item->qty;
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
                        ->where('id_gudang', $request->kodeGudang)->first();
                    $updateStok->{'stok'} -= $item->qty;
                    $updateStok->save();
                }
            }

            /*f or($i = 0; $i < $items->count(); $i++) {
                if($items[$i]->id_barang != $detil[$i]->id_barang) {
                    $updateStok = StokBarang::where('id_barang', $items[$i]->id_barang)
                                ->where('id_gudang', $request->kodeGudang)->first();
                    $updateStok->{'stok'} -= $items[$i]->qty;
                    $updateStok->save();
                }
            } */
        }


        $data = [
            'id' => $request->kode,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        $url = Route('bm-show', $data);
        return redirect($url);
    }
}
