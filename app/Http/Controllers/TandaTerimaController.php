<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TandaTerima;
use App\Models\SalesOrder;
use Carbon\Carbon;
use PDF;

class TandaTerimaController extends Controller
{
    public function index() {
        $items = TandaTerima::groupBy('id')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.penjualan.tandaterima.index', $data);
    }

    public function detail(Request $request, $id) {
        $items = TandaTerima::groupBy('id')->get();
        $data = [
            'items' => $items,
            'kode' => $id
        ];

        return view('pages.penjualan.tandaterima.detail', $data);
    }

    public function indexCetak($status, $awal, $akhir) {
        $ttr = TandaTerima::select('id_so')->get()->pluck('id_so')->toArray();
        // var_dump($ttr);
        $items = SalesOrder::whereNotIn('id', $ttr)->where('status', 'CETAK')->get();
        // return response()->json($items);

        $data = [
            'items' => $items,
            'status' => $status,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.penjualan.tandaterima.indexCetak', $data);
    }

    public function process(Request $request) {
        $data = [
            'awal' => $request->kodeAwal,
            'akhir' => $request->kodeAkhir,
            'status' => 'true'
        ];

        return redirect()->route('ttr-index-cetak', $data);
    }

    public function cetak($awal, $akhir) {
        $items = SalesOrder::where('status', 'CETAK')->whereBetween('id', [$awal, $akhir])
                ->get();

        $lastcode = TandaTerima::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TTR'.sprintf('%04s', $lastnumber);

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'newcode' => $newcode,
            'today' => $today,
            'waktu' => $waktu
        ];

        $paper = array(0,0,612,394);
        $pdf = PDF::loadview('pages.penjualan.tandaterima.cetak', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-ttr.pdf');
    }
}
