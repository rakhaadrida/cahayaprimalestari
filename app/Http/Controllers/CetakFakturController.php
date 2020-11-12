<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use PDF;

class CetakFakturController extends Controller
{
    public function index($status, $awal, $akhir) {
        $items = SalesOrder::whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])->get();
        $data = [
            'items' => $items,
            'status' => $status,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.penjualan.cetakfaktur.index', $data);
    }

    public function process(Request $request) {
        $data = [
            'awal' => $request->kodeAwal,
            'akhir' => $request->kodeAkhir,
            'status' => 'true'
        ];

        return redirect()->route('cetak-faktur', $data);
    }

    public function cetak($awal, $akhir) {
        $items = SalesOrder::with(['customer'])->where('status', 'INPUT')->whereBetween('id', [$awal, $akhir])->get();

        $data = [
            'items' => $items
        ];

        $paper = array(0,0,686,394);
        $pdf = PDF::loadview('pages.penjualan.cetakfaktur.cetak', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    } 

    /* public function cetak(Request $request) {
        $items = SalesOrder::with(['customer'])->where('status', 'INPUT')
                    ->whereBetween('id', [$request->kodeAwal, $request->kodeAkhir])->get();

        $data = [
            'items' => $items
        ];

        $paper = array(0,0,686,394);
        $pdf = PDF::loadview('pages.penjualan.cetakAll', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    } */

    public function update($awal, $akhir) {
        $items = SalesOrder::whereBetween('id', [$awal, $akhir])->get();

        foreach($items as $item) {
            $item->status = 'CETAK';
            $item->save();
        }

        $items = SalesOrder::where('status', 'INPUT')->get();

        $data = [
            'items' => $items,
            'status' => 'false',
            'awal' => 0,
            'akhir' => 0
        ];

        return view('pages.penjualan.cetakfaktur.index', $data);
    }
}
