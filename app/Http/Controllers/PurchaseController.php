<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\Supplier;
use App\Barang;
use App\HargaBarang;
use App\DetilPO;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index() {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        // autonumber
        $lastcode = PurchaseOrder::max('id');
        // $lastnumber = (int) substr($lastcode, 3, 2);
        $lastcode++;
        $newcode = 'PO'.sprintf('%02s', $lastcode);

        $items = DetilPO::with('barang')->where('id_po', $newcode)->get();
        $itemsRow = DetilPO::where('id_po', $newcode)->count();
        // $items = PurchaseOrder::with(['supplier', 'barang'])->where('id', $newcode)->first();

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
        DetilPO::create([
            'id_po' => $id,
            'id_barang' => $request->kodeBarang,
            'harga' => $request->harga,
            'qty' => $request->pcs
        ]);

        // $data = $request->all();
        // $data['id_po'] = $id;

        // DetilPO::create($data);

        return redirect()->route('po');
    }
}
