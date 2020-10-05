<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\DetilPO;
use App\Models\Barang;
use App\Models\Supplier;
use Carbon\Carbon;

class BMController extends Controller
{
    public function index() {
        $po = PurchaseOrder::All();

        $data = [
            'po' => $po
        ];

        return view('pages.pembelian.barangMasuk', $data);
    }

    public function process(Request $request) {
        $po = PurchaseOrder::All();
        $itemPo = PurchaseOrder::find($request->kode);
        $items = DetilPO::where('id_po', $request->kode)->get();
        $tanggal = Carbon::now()->toDateString();
        $tanggal = Carbon::parse($tanggal)->format('d-m-Y');

        $data = [
            'po' => $po,
            'itemPo' => $itemPo,
            'items' => $items,
            'tanggal' => $tanggal
        ];

        return view('pages.pembelian.detilMasuk', $data);
    }

    public function create(Request $request, $id) {
        $itemPo = PurchaseOrder::find($id);
        $items = DetilPO::where('id_po', $id)->get();
        $itemsRow = DetilPO::where('id_po', $id)->count();

        $i = 0;
        $sama = 0;
        foreach($items as $item) {
            $item->qty_terima = $request->qtyDikirim[$i];
            $item->keterangan = $request->keterangan[$i];
            $item->save();

            if($item->qty == $request->qtyDikirim[$i]) {
                $sama++;
            }
            $i++;
        }

        if($sama == $itemsRow) {
            $itemPo->status = 'LENGKAP';
            $itemPo->save();
        }

        return redirect()->route('barangMasuk');
    }
}
