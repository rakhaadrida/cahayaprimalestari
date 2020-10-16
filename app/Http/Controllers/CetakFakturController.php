<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use PDF;

class CetakFakturController extends Controller
{
    public function index() {
        $items = SalesOrder::where('status', 'INPUT')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.penjualan.cetakFaktur', $data);
    }

    public function cetak(Request $request) {
        $items = SalesOrder::with(['customer'])->where('status', 'INPUT')->whereBetween('id', [$request->kodeAwal, $request->kodeAkhir])->get();

        $data = [
            'items' => $items
        ];

        $paper = array(0,0,686,394);
        $pdf = PDF::loadview('pages.penjualan.cetakAll', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    }
}
