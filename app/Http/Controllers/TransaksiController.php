<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;

class TransaksiController extends Controller
{
    public function index() {
        return view('pages.penjualan.transaksi');
    }

    public function show(Request $request) {
        $items = SalesOrder::with('customer')
                ->whereBetween('tgl_so', [$request->tglAwal, $request->tglAkhir])
                ->orderBy('id', 'asc')->get();
        
        $data = [
            'items' => $items,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.showTrans', $data);
    }

    public function detail(Request $request, $id) {
        $items = SalesOrder::with('customer')->where('id', $id)
                ->orWhereBetween('tgl_so', [$request->tglAwal, $request->tglAkhir])
                ->orderBy('id', 'asc')->get();
        
        $data = [
            'items' => $items,
            'kode' => $id,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.detilTrans', $data);
    }
}
