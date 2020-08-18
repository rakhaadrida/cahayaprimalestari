<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\DetilPO;
use App\Barang;
use App\Supplier;
use Carbon\Carbon;

class BarangMasukController extends Controller
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
}
