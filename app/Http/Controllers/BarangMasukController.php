<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\Barang;
use App\HargaBarang;
use App\BarangMasuk;
use App\DetilBM;
use App\TempDetilBM;
use App\StokBarang;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    public function index() {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        // autonumber
        $lastcode = BarangMasuk::max('id');
        $lastnumber = (int) substr($lastcode, 2, 4);
        $lastnumber++;
        $newcode = 'BM'.sprintf('%04s', $lastnumber);

        $items = TempDetilBM::with(['barang', 'supplier'])->where('id_bm', $newcode)->get();
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
            'harga' => $harga
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
        
        BarangMasuk::create([
            'id' => $id,
            'tanggal' => $tanggal,
            'id_supplier' => $request->kodeSupplier,
            'status' => 'COMPLETE'
        ]);

        $tempDetil = TempDetilBM::where('id_bm', $id)->get();
        foreach($tempDetil as $td) {
            DetilBM::create([
                'id_bm' => $td->id_bm,
                'id_barang' => $td->id_barang,
                'harga' => $td->harga,
                'qty' => $td->qty,
                'keterangan' => $td->keterangan
            ]);

            $updateStok = StokBarang::where('id_barang', $td->id_barang)
                            ->where('id_gudang', 'GDG01')->first();
            $updateStok->{'stok'} = $updateStok->{'stok'} + $td->qty;
            $updateStok->save();

            $deleteTemp = TempDetilBM::where('id_bm', $id)->where('id_barang', $td->id_barang)->delete();
        }

        return redirect()->route('barangMasuk');
    }

    public function update(Request $request) {
        $items = $request->all();

        foreach($items as $item) {
            $updateTemp = TempDetilBM::find($item['barang']);
            $updateTemp->{'qty'} = $item['qty'];
            $updateTemp->save();
        }

        return redirect()->route('po');
    }

    public function remove($bm, $barang) {
        $tempDetil = TempDetilBM::where('id_bm', $bm)->where('id_barang', $barang)->delete();

        return redirect()->route('barangMasuk');
    }
}
