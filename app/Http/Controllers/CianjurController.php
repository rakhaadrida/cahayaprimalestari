<?php

namespace App\Http\Controllers;

use App\Models\AccReceivable;
use App\Models\Approval;
use App\Models\AR_Retur;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\DetilAR;
use App\Models\DetilSO;
use App\Models\Gudang;
use App\Models\Harga;
use App\Models\HargaBarang;
use App\Models\NeedAppDetil;
use App\Models\NeedApproval;
use App\Models\SalesOrder;
use App\Models\Faktur;
use App\Models\FakturItem;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CianjurController extends Controller
{
    public function so() {
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $stok = StokBarang::query()
            ->join('gudang', 'gudang.id', 'stok.id_gudang')
            ->where('tipe', 'TOKO')
            ->get();

        $gudang = Gudang::where('tipe', 'TOKO')->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastInvoice = Faktur::query()
            ->selectRaw('max(nomor) as number')
            ->whereYear('created_at', $waktu->year)
            ->whereMonth('created_at', $month)
            ->get();

        $lastnumber = (int) substr($lastInvoice[0]->number, 6, 4);
        $lastnumber++;

        $newNumber = 'FT'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'barang' => $barang,
            'harga' => $harga,
            'stok' => $stok,
            'gudang' => $gudang,
            'newNumber' => $newNumber,
            'tanggal' => $tanggal
        ];

        return view('pages.cianjur.so.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function process(Request $request, $id) {
        try {
            DB::beginTransaction();

            $jumlah = $request->jumBaris;

            $tanggal = $request->tanggal;
            $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');

            $waktu = Carbon::now('+07:00');
            $bulan = $waktu->format('m');
            $month = $waktu->month;
            $tahun = substr($waktu->year, -2);

            $lastInvoice = Faktur::query()
                ->selectRaw('MAX(nomor) as number')
                ->whereYear('created_at', $waktu->year)
                ->whereMonth('created_at', $month)
                ->get();

            $lastnumber = (int) substr($lastInvoice[0]->number, 6, 4);
            $lastnumber++;

            $newNumber = 'FT'.$tahun.$bulan.sprintf('%04s', $lastnumber);
            $number = $newNumber;

            $invoice = Faktur::create([
                'nomor' => $number,
                'tanggal' => $tanggal,
                'total' => 0,
                'id_user' => Auth::user()->id,
                'id_cabang' => 3
            ]);

            $subtotal = 0;
            for($i = 0; $i < $jumlah; $i++) {
                if(($request->kodeBarang[$i] != "") && ($request->qty[$i] != "")) {
                    $quantity = $request->qty[$i];
                    $price = str_replace(".", "", $request->harga[$i]);
                    $total = $quantity * $price;    

                    $invoice->items()->create([
                        'id_barang' => $request->kodeBarang[$i],
                        'qty' => $quantity,
                        'harga' => $price,
                        'jumlah' => $total
                    ]);

                    $subtotal += $total;

                    $updateStok = StokBarang::query()
                        ->where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $request->kodeGudang[$i])
                        ->first();

                    $updateStok->{'stok'} -= $request->qty[$i];
                    $updateStok->save();
                }
            }

            $invoice->update([
                'total' => $subtotal
            ]);

            DB::commit();

            return redirect()->route('so-cianjur');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'message' => 'An error occurred while saving data'
            ]);
        }
    }

    public function cetak(Request $request, $id) {
        $items = SalesOrder::with(['customer'])->where('id', $id)->get();
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
                    'id_user' => $items->first()->id_user
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

        return view('pages.kenari.so.cetakInv', $data);
        // return view('pages.kenari.so.cetakPdf', $data);
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
                ->select('so.id as id', 'so.*')->where('tgl_so', '>', Carbon::now()->subMonths(1))
                ->where('roles', 'KENARI')->orderBy('created_at', 'desc')->get();
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
            'tipe' => 'Faktur',
            'id_user' => Auth::user()->id
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
            'tanggal' => Carbon::now('+07:00'),
            'status' => 'PENDING_UPDATE',
            'keterangan' => $request->keterangan,
            'id_dokumen' => $request->kode,
            'tipe' => 'Faktur',
            'id_user' => Auth::user()->id
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

            /* for($i = 0; $i < $items->count(); $i++) {
                if($items[$i]->id_barang != $detil[$i]->id_barang) {
                    $updateStok = StokBarang::where('id_barang', $items[$i]->id_barang)
                                ->where('id_gudang', $items[$i]->id_gudang)->first();
                    $updateStok->{'stok'} += $items[$i]->qty;
                    $updateStok->save();
                }
            } */
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

        // return redirect()->route('cetak-faktur-kenari', $data);
        return redirect()->route('cetak-all-kenari', ['awal' => $request->kodeAwal, 'akhir' => $request->kodeAkhir]);
    }

    public function cetakFaktur($awal, $akhir) {
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                ->whereBetween('so.id', [$awal, $akhir])->where('roles', 'KENARI')
                ->orderBy('tgl_so', 'asc')->get();

        foreach($items as $i) {
            $item = SalesOrder::where('id', $i->id)->get();
            $tabel = ceil($item->first()->detilso->count() / 12);

            if($tabel > 1) {
                for($j = 1; $j < $tabel; $j++) {
                    $newItem = collect([
                        'id' => $item->first()->id.'Z',
                        'tgl_so' => $item->first()->tgl_so,
                        'tgl_kirim' => $item->first()->tgl_kirim,
                        'total' => $item->first()->total,
                        'diskon' => $item->first()->diskon,
                        'kategori' => $item->first()->kategori,
                        'tempo' => $item->first()->tempo,
                        'id_customer' => $item->first()->id_customer,
                        'id_sales' => $item->first()->id_sales,
                        'id_user' => $item->first()->id_user,
                    ]);

                    $items->push($newItem);
                }
            }
        }

        $items = $items->sortBy(function ($product, $key) {
                    return $product['tgl_so'].$product['id'];
                });
        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.kenari.cetakfaktur.cetakInv', $data);
        // return view('pages.kenari.cetakfaktur.cetakPdf', $data);
    }

    public function updateFaktur($awal, $akhir) {
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', 'KENARI')
                ->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                ->whereBetween('so.id', [$awal, $akhir])->get();

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

    public function indexNotif() {
        $items = Approval::with(['so', 'bm'])
                ->select('id', 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe', 'baca')
                ->where('baca', 'F')->latest()->get();

        $data = [
            'items' => $items
        ];

        return view('pages.kenari.notif.index', $data);
    }

    public function showNotif($id) {
        $gudang = Gudang::where('tipe', 'KENARI')->get();
        $items = Approval::with(['so', 'bm'])
                ->select('id', 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe', 'baca')
                ->where('baca', 'F')->get();

        $cicilPerCust = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(cicil) as totCicil'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $totalPerCust = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(total) as totKredit'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $returPerCust = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(ar_retur.total) as totRetur'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        foreach($totalPerCust as $q) {
            $q['total'] = $q->totKredit;
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] -= $h->totCicil;
                }
            }
            foreach($returPerCust as $r) {
                if($q->id_customer == $r->id_customer) {
                    $q['total'] -= $h->totCicil;
                }
            }
        }

        foreach($totalPerCust as $q) {
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] = $q->totKredit - $h->totCicil;
                }
            }
        }

        $data = [
            'gudang' => $gudang,
            'notif' => $items,
            'kode' => $id,
            'total' => $totalPerCust
        ];

        return view('pages.kenari.notif.show', $data);
    }

    public function markAsRead($id) {
        $item = Approval::where('id', $id)->first();
        $item->{'baca'} = 'T';
        $item->save();

        return redirect()->route('notif-kenari');
    }
}
