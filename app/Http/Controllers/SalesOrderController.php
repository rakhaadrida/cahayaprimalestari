<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\DetilSO;
use App\Models\NeedApproval;
use App\Models\HargaBarang;
use App\Models\Gudang;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use PDF;

class SalesOrderController extends Controller
{
    public function index(Request $request) {
        $customer = Customer::with(['sales'])->get();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $stok = StokBarang::All();
        $gudang = Gudang::All();

        $lastcode = SalesOrder::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'INV'.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $items = TempDetilSO::with(['barang', 'customer'])
                            ->where('id_so', $newcode)->latest()->get();
        $itemsRow = TempDetilSO::where('id_so', $newcode)->count();

        $data = [
            'customer' => $customer,
            'barang' => $barang,
            'harga' => $harga,
            'stok' => $stok,
            'gudang' => $gudang,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'itemsRow' => $itemsRow,
            'items' => $items
        ];

        return view('pages.penjualan.so', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function create(Request $request, $id) {
        NeedApproval::create([
            'id_so' => $id,
            'id_barang' => $request->kodeBarang,
            'harga' => $request->harga,
            'qty' => $request->pcs,
            'diskon' => $request->diskon,
            'id_customer' => $request->kodeCustomer,
            'tgl_kirim' => $request->tanggalKirim,
            'tempo' => $request->tempo,
            'pkp' => $request->pkp
        ]);

        return redirect()->route('so');
    }

    public function process(Request $request, $id, $status) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;
        
        SalesOrder::create([
            'id' => $id,
            'tgl_so' => $tanggal,
            'tgl_kirim' => $request->tanggalKirim,
            'total' => str_replace(".", "", $request->grandtotal),
            'kategori' => $request->kategori,
            'tempo' => $request->tempo,
            'pkp' => $request->pkp,
            'status' => $status,
            'id_customer' => $request->kodeCustomer
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                $arrGudang = explode(",", $request->kodeGudang[$i]);
                $arrStok = explode(",", $request->qtyGudang[$i]);
                for($j = 0; $j < sizeof($arrGudang); $j++) {
                    DetilSO::create([
                        'id_so' => $id,
                        'id_barang' => $request->kodeBarang[$i],
                        'id_gudang' => $arrGudang[$j],
                        'harga' => str_replace(".", "", $request->harga[$i]),
                        'qty' => $arrStok[$j],
                        'diskon' => $request->diskon[$i]
                    ]);

                    // $qty = $qtyGudang[$j];

                    $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $arrGudang[$j])->first();
                                var_dump($updateStok);
                    $updateStok->{'stok'} -= $arrStok[$j];
                    $updateStok->save();
                    // foreach($updateStok as $us) {
                    //     if($request->qty[$i] <= $us->stok) {
                    //         $us->stok -= $request->qty[$i];
                    //     }
                    //     else {
                    //         $qty -= $us->stok;
                    //         $us->stok -= $us->stok;
                    //     }
                    //     $us->save();
                    // }
                }
            }
        }

        // $this->cetak($id);
        return redirect()->route('so');
        // return redirect()->route('so-cetak', $id);
    }

    public function cetak(Request $request, $id) {
        $items = DetilSO::with(['so', 'barang'])->where('id_so', $id)->get();
        $data = [
            'items' => $items
        ];

        $paper = array(0,0,686,394);
        $pdf = PDF::loadview('pages.penjualan.cetakSO', $data)->setPaper($paper);
        return $pdf->stream('cetak-so.pdf');
    }

    public function remove($id, $barang) {
        $tempDetil = TempDetilSO::where('id_so', $id)->where('id_barang', $barang)->delete();

        return redirect()->route('so');
    }

    public function change() {
        $so = SalesOrder::All();
        $customer = Customer::All();

        $data = [
            'so' => $so,
            'customer' => $customer
        ];

        return view('pages.penjualan.ubahFaktur', $data);
    }

    public function show(Request $request) {
        $items = SalesOrder::with('customer')->where('id', $request->id)
                ->orWhere('id_customer', $request->kode)
                ->orWhereBetween('tgl_so', [$request->tglAwal, $request->tglAkhir])
                ->orderBy('id', 'asc')->get();
        
        $itemsRow = SalesOrder::where('id', $request->id)
                    ->orWhere('id_customer', $request->kode)
                    ->orWhereBetween('tgl_so', [$request->tglAwal, $request->tglAkhir])
                    ->count();
        $customer = Customer::All();
        $so = SalesOrder::All();
        
        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'customer' => $customer,
            'so' => $so,
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.detilFaktur', $data);
    }

    public function status(Request $request, $id) {
        $item = SalesOrder::where('id', $id)->first();
        $item->{'status'} = $request->statusUbah;
        $item->{'keterangan'} = $request->keterangan;
        $item->save();

        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');  
    }

    public function edit(Request $request, $id) {
        $items = DetilSO::with(['so', 'barang'])->where('id_so', $id)->get();
        $itemsRow = DetilSO::where('id_so', $id)->count();
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.updateFaktur', $data);
    }

    public function update(Request $request) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        $items = SalesOrder::where('id', $request->kode)->first();
        $items->{'status'} = 'PENDING_UPDATE';
        $items->save();

        for($i = 0; $i < $jumlah; $i++) {
            NeedApproval::create([
                'id_so' => $request->kode,
                'id_barang' => $request->kodeBarang[$i],
                'harga' => str_replace(".", "", $request->harga[$i]),
                'qty' => $request->qty[$i],
                'diskon' => $request->diskon[$i],
                'status' => 'PENDING_UPDATE',
                'keterangan' => $request->keterangan
            ]);
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
