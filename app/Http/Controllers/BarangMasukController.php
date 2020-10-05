<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\HargaBarang;
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use App\Models\TempDetilBM;
use App\Models\StokBarang;
use App\Models\Gudang;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    public function index() {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $gudang = Gudang::All();

        // autonumber
        $lastcode = BarangMasuk::max('id');
        $lastnumber = (int) substr($lastcode, 2, 4);
        $lastnumber++;
        $newcode = 'BM'.sprintf('%04s', $lastnumber);

        $items = TempDetilBM::with(['barang', 'supplier'])->where('id_bm', $newcode)
                    ->orderBy('created_at','asc')->get();
        $itemsRow = TempDetilBM::where('id_bm', $newcode)->count();

        // date now
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'supplier' => $supplier,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'gudang' => $gudang
        ];

        return view('pages.pembelian.barangMasuk', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function create(Request $request, $id) {
        TempDetilBM::create([
            'id_bm' => $id,
            'id_barang' => $request->kodeBarang,
            'harga' => $request->harga,
            'qty' => $request->pcs,
            'keterangan' => $request->ket,
            'id_supplier' => $request->kodeSupplier
        ]);

        return redirect()->route('barangMasuk');
    }

    public function process (Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;
        
        BarangMasuk::create([
            'id' => $id,
            'tanggal' => $tanggal,
            'id_supplier' => $request->kodeSupplier,
            'status' => 'COMPLETE'
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                DetilBM::create([
                    'id_bm' => $request->id,
                    'id_barang' => $request->kodeBarang[$i],
                    'harga' => str_replace(".", "", $request->harga[$i]),
                    'qty' => $request->qty[$i],
                    'diskon' => $request->diskon[$i],
                    'keterangan' => ""
                ]);
            }
        }

        /*
        $tempDetil = TempDetilBM::where('id_bm', $id)->get();
        foreach($tempDetil as $td) {
            DetilBM::create([
                'id_bm' => $td->id_bm,
                'id_barang' => $td->id_barang,
                'harga' => str_replace(".", "", $td->harga),
                'qty' => $td->qty,
                'keterangan' => $td->keterangan
            ]);

            $updateStok = StokBarang::where('id_barang', $td->id_barang)
                            ->where('id_gudang', 'GDG01')->first();
            $updateStok->{'stok'} = $updateStok->{'stok'} + $td->qty;
            $updateStok->save();

            $deleteTemp = TempDetilBM::where('id_bm', $id)->where('id_barang', $td->id_barang)->delete();
        }
        */

        return redirect()->route('barangMasuk');
    }

    public function update(Request $request, $bm, $barang, $id) {
        $updateDetil = TempDetilBM::where('id_bm', $bm)->where('id_barang', $barang)->first();

        $updateDetil->{'qty'} = $request->editQty[$id-1];
        $updateDetil->{'keterangan'} = $request->editKet[$id-1];
        $updateDetil->save();

        return redirect()->route('barangMasuk');
    }

    public function remove($bm, $barang) {
        $tempDetil = TempDetilBM::where('id_bm', $bm)->where('id_barang', $barang)->delete();

        return redirect()->route('barangMasuk');
    }

    public function reset($bm) {
         $tempDetil = TempDetilBM::where('id_bm', $bm)->delete();

        return redirect()->route('barangMasuk');
    }
}
