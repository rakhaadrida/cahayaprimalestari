<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\Supplier;
use App\Barang;
use App\HargaBarang;
use App\DetilPO;
use App\TempDetil;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index() {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        // autonumber
        $lastcode = PurchaseOrder::max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'PO'.sprintf('%02s', $lastnumber);

        $items = TempDetil::with(['barang', 'supplier'])->where('id_po', $newcode)->get();
        $itemsRow = TempDetil::where('id_po', $newcode)->count();

        // date now
        $tanggal = Carbon::now()->toDateString();
        $tanggal = Carbon::parse($tanggal)->format('d-m-Y');

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

    public function create(Request $request, $id) {
        TempDetil::create([
            'id_po' => $id,
            'id_barang' => $request->kodeBarang,
            'harga' => $request->harga,
            'qty' => $request->pcs,
            'id_supplier' => $request->kodeSupplier
        ]);

        return redirect()->route('po');
    }

    public function process (Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = Carbon::parse($tanggal)->format('Y-m-d');
        
        PurchaseOrder::create([
            'id' => $id,
            'tgl_po' => $tanggal,
            'id_supplier' => $request->kodeSupplier,
            'total' => $request->grandtotal,
            'status' => 'PENDING'
        ]);

        $tempDetil = TempDetil::where('id_po', $id)->get();
        foreach($tempDetil as $td) {
            DetilPO::create([
                'id_po' => $td->id_po,
                'id_barang' => $td->id_barang,
                'harga' => $td->harga,
                'qty' => $td->qty
            ]);

            $deleteTemp = TempDetil::where('id_po', $id)->where('id_barang', $td->id_barang)->delete();
        }

        return redirect()->route('po');
    }

    public function update(Request $request) {
        $items = $request->all();

        foreach($items as $item) {
            $updateTemp = TempDetil::find($item['barang']);
            $updateTemp->{'qty'} = $item['qty'];
            $updateTemp->save();
        }

        return redirect()->route('po');
    }

    public function remove($po, $barang) {
        $tempDetil = TempDetil::where('id_po', $po)->where('id_barang', $barang)->delete();

        return redirect()->route('po');
    }
}
