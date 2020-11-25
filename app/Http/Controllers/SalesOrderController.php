<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Customer;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use PDF;

class SalesOrderController extends Controller
{
    public function index($status) {
        $customer = Customer::with(['sales'])->get();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $hrg = Harga::All();
        $stok = StokBarang::All();
        $gudang = Gudang::All();

        $lastcode = SalesOrder::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'INV'.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $cicilPerCust = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(cicil) as totCicil'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $totalPerCust = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(total - retur) as totKredit'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        foreach($totalPerCust as $q) {
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] = $q->totKredit - $h->totCicil;
                } else {
                    $q['total'] = $q->totKredit - 0;
                }
            }
        }

        // return response()->json($totalPerCust);

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
            'lastcode' => $lastcode,
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

        $lastcode = AccReceivable::max('id');
        $lastnumber = (int) substr($lastcode, 2, 4);
        $lastnumber++;
        $newcode = 'AR'.sprintf('%04s', $lastnumber);
        
        SalesOrder::create([
            'id' => $id,
            'tgl_so' => $tanggal,
            'tgl_kirim' => $tglKirim,
            'total' => str_replace(".", "", $request->grandtotal),
            'diskon' => str_replace(".", "", $diskon),
            'kategori' => $request->kategori,
            'tempo' => $tempo,
            'pkp' => $pkp,
            'status' => $status,
            'id_customer' => $request->kodeCustomer
        ]);

        if($status != 'LIMIT') {
            AccReceivable::create([
                'id' => $newcode,
                'id_so' => $id,
                'retur' => 0,
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
            if($request->kodeBarang[$i] != "") {
                $arrGudang = explode(",", $request->kodeGudang[$i]);
                $arrStok = explode(",", $request->qtyGudang[$i]);
                $diskonRp = str_replace(".", "", $request->diskonRp[$i]) / sizeof($arrGudang);
                for($j = 0; $j < sizeof($arrGudang); $j++) {
                    DetilSO::create([
                        'id_so' => $id,
                        'id_barang' => $request->kodeBarang[$i],
                        'id_gudang' => $arrGudang[$j],
                        'harga' => str_replace(".", "", $request->harga[$i]),
                        'qty' => $arrStok[$j],
                        'diskon' => $request->diskon[$i],
                        'diskonRp' => $diskonRp
                    ]);

                    // if($status == 'LIMIT') {
                    //     NeedAppDetil::create([
                    //         'id_app' => $newcode,
                    //         'id_barang' => $request->kodeBarang[$i],
                    //         'id_gudang' => $arrGudang[$j],
                    //         'harga' => str_replace(".", "", $request->harga[$i]),
                    //         'qty' => $arrStok[$j],
                    //         'diskon' => $request->diskon[$i],
                    //     ]);
                    // }

                    $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $arrGudang[$j])->first();
                    // var_dump($updateStok);
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
            }
        }

        if($status != 'CETAK')
            $cetak = 'false';
        else
            $cetak = 'true';

        return redirect()->route('so', $cetak);
    }

    public function cetak(Request $request, $id) {
        $items = SalesOrder::with(['customer'])->where('id', $id)->get();
        $data = [
            'items' => $items
        ];

        $paper = array(0,0,686,394);
        $pdf = PDF::loadview('pages.penjualan.so.cetak', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-so.pdf');
    }

    public function change() {
        $so = SalesOrder::All();
        $customer = Customer::All();

        $data = [
            'so' => $so,
            'customer' => $customer
        ];

        return view('pages.penjualan.ubahfaktur.index', $data);
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
            $items = SalesOrder::with(['customer', 'need_approval'])
                    ->where('id', $request->id)
                    ->orWhere('id_customer', $request->kode)
                    ->orWhereBetween('tgl_so', [$tglAwal, $tglAkhir])
                    ->orderBy('id', 'asc')->get();
        } else {
            $items = SalesOrder::with(['customer', 'need_approval'])
                    ->where('id_customer', $request->kode)
                    ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                    ->orWhere('id', $request->id)
                    ->orderBy('id', 'asc')->get();
        }
        
        $customer = Customer::All();
        $gudang = Gudang::All();
        $stok = StokBarang::All();
        $so = SalesOrder::All();
        
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

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'PENDING_BATAL',
            'keterangan' => $request->input("ket".$id),
            'id_dokumen' => $id,
            'tipe' => 'Faktur'
        ]);

        $items = NeedApproval::with(['need_appdetil'])->where('id_dokumen', $id)->get();

        if($items[0]->need_appdetil->count() != 0) {
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
        $gudang = Gudang::All();
        $stok = StokBarang::All();

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
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
                    'diskonRp' => $diskonRp
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
