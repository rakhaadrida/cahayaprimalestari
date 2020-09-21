<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\Supplier;
use App\Barang;
use App\HargaBarang;
use App\DetilPO;
use App\TempDetilPO;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index() {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        // autonumber
        $lastcode = PurchaseOrder::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'PO'.sprintf('%04s', $lastnumber);

        $items = TempDetilPO::with(['barang', 'supplier'])->where('id_po', $newcode)->get();
        $itemsRow = TempDetilPO::where('id_po', $newcode)->count();

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

        return view('pages.poAlter', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function process (Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        PurchaseOrder::create([
            'id' => $id,
            'tgl_po' => $tanggal,
            'id_supplier' => $request->kodeSupplier,
            'total' => $request->grandtotal,
            'status' => 'PENDING'
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                DetilPO::create([
                    'id_po' => $id,
                    'id_barang' => $request->kodeBarang[$i],
                    'harga' => $request->harga[$i],
                    'qty' => $request->qty[$i]
                ]);
            }
        }

        return redirect()->route('po');
    }

    public function update(Request $request) {
        $items = $request->all();

        foreach($items as $item) {
            $updateTemp = TempDetilPO::find($item['barang']);
            $updateTemp->{'qty'} = $item['qty'];
            $updateTemp->save();
        }

        return redirect()->route('po');
    }

    public function remove($po, $barang) {
        $tempDetil = TempDetilPO::where('id_po', $po)->where('id_barang', $barang)->delete();

        return redirect()->route('po');
    }
}
