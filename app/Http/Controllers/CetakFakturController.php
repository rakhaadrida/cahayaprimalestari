<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use PDF;

class CetakFakturController extends Controller
{
    public function index($status, $awal, $akhir) {
        $items = SalesOrder::where('status', 'INPUT')->get();
        $data = [
            'items' => $items,
            'status' => $status,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.penjualan.cetakFaktur', $data);
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

        foreach($items as $i) {
            AccReceivable::create([
                'id_so' => $i->id,
                'tgl_bayar' => NULL,
                'cicil' => NULL,
                'retur' => NULL,
                'keterangan' => 'BELUM LUNAS'
            ]);
        }

        $data = [
            'items' => $items
        ];

        $paper = array(0,0,686,394);
        $pdf = PDF::loadview('pages.penjualan.cetakAll', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-all.pdf');
    }
}
