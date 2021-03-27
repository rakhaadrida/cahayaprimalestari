<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use Carbon\Carbon;
use PDF;

class CetakBMController extends Controller
{
    public function index($status, $awal, $akhir) {
        $items = BarangMasuk::whereIn('status', ['INPUT', 'UPDATE'])->orderBy('tanggal', 'asc')->get();
        $data = [
            'items' => $items,
            'status' => $status,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.pembelian.cetakBM.index', $data);
    }

    public function detail(Request $request, $id) {
        // $items = BarangMasuk::where('id', $id)->get();
        $items = BarangMasuk::whereIn('status', ['INPUT', 'UPDATE'])->get();
        
        $data = [
            'items' => $items,
            'kode' => $id
        ];

        return view('pages.pembelian.cetakBM.detail', $data);
    }

    public function process(Request $request) {
        $data = [
            'awal' => $request->kodeAwal,
            'akhir' => $request->kodeAkhir,
            'status' => 'true'
        ];

        return redirect()->route('cetak-bm', $data);
    }

    public function cetak($awal, $akhir) {
        $items = BarangMasuk::whereIn('status', ['INPUT', 'UPDATE'])->whereBetween('id', [$awal, $akhir])
                ->orderBy('tanggal', 'asc')->get();

        foreach($items as $i) {
            $item = BarangMasuk::where('id', $i->id)->get();
            $tabel = ceil($item->first()->detilbm->count() / 34);

            if($tabel > 1) {
                for($j = 1; $j < $tabel; $j++) {
                    $newItem = collect([
                        'id' => $item->first()->id.'Z',
                        'id_faktur' => $item->first()->id_faktur,
                        'tanggal' => $item->first()->tanggal,
                        'total' => $items->first()->total,
                        'id_supplier' => $items->first()->id_supplier,
                        'id_user' => $items->first()->id_user,
                    ]);

                    $items->push($newItem);
                }
            }
        }   

        $items = $items->sortBy(function ($product, $key) {
                    return $product['tanggal'].$product['id'];
                });
        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.pembelian.cetakBM.cetakPdf', $data);

        // $paper = array(0,0,612,394);
        // // $paper = letter, portrait;
        // $pdf = PDF::loadview('pages.pembelian.cetakBM.cetak', $data)->setPaper($paper);
        // ob_end_clean();
        // return $pdf->stream('cetak-all.pdf');
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
        $items = BarangMasuk::whereBetween('id', [$awal, $akhir])->get();

        foreach($items as $item) {
            $item->status = 'CETAK';
            $item->save();
        }

        $data = [
            'status' => 'false',
            'awal' => 0,
            'akhir' => 0
        ];

        return redirect()->route('cetak-bm', $data);
    }
}
