<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\TandaTerima;
use App\Models\Approval;
use Carbon\Carbon;
use PDF;

class CetakFakturController extends Controller
{
    public function index($status, $awal, $akhir) {
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')
                ->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                ->orderBy('tgl_so', 'asc')->get();

        // $items = SalesOrder::join('users', 'users.id', 'so.id_user')
        //         ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')
        //         ->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
        //         ->whereBetween('so.id', [$awal, $akhir])
        //         ->orderBy('tgl_so', 'asc')->get();

        // foreach($items as $i) {
        //     $item = SalesOrder::where('id', $i->id)->get();
        //     $tabel = ceil($item->first()->detilso->count() / 12);

        //     if($tabel > 1) {
        //         for($j = 1; $j < $tabel; $j++) {
        //             $newItem = collect([
        //                 'id' => $item->first()->id.'Z',
        //                 'tgl_so' => $item->first()->tgl_so,
        //                 'tgl_kirim' => $item->first()->tgl_kirim,
        //                 'total' => $item->first()->total,
        //                 'diskon' => $item->first()->diskon,
        //                 'kategori' => $item->first()->kategori,
        //                 'tempo' => $item->first()->tempo,
        //                 'id_customer' => $item->first()->id_customer,
        //                 'id_user' => $item->first()->id_user,
        //             ]);

        //             $items->push($newItem);
        //         }
        //     }
        // }   

        // // $items = $items->sortBy('tgl_so', SORT_NATURAL);
        // $items = $items->sortBy(function ($product, $key) {
        //             return $product['tgl_so'].$product['id'];
        //         });
        // $items = $items->values();

        // return response()->json($items);

        // $itemsDet = \App\Models\DetilSO::with(['barang'])
        //             ->select('id_barang', 'diskon')
        //             ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
        //             ->where('id_so', 'IV21001281')
        //             // ->whereNotIn('id_barang', $kode)
        //             ->groupBy('id_barang', 'diskon')
        //             ->get();
        // return response()->json($itemsDet);

        // $itemsBar = \App\Models\DetilSO::with(['barang'])
        //             ->select('id_barang', 'diskon')
        //             // ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
        //             ->where('id_so', 'IV21001281')
        //             // ->whereNotIn('id_barang', $kode)
        //             ->get();

        //             return response()->json($itemsBar);

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
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')
                ->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                ->whereBetween('so.id', [$awal, $akhir])
                ->orderBy('tgl_so', 'asc')->get();

        foreach($items as $i) {
            $item = SalesOrder::where('id', $i->id)->get();
            $detil = DetilSO::select('id_barang', 'diskon')
                    ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                    ->where('id_so', $item->first()->id)
                    ->groupBy('id_barang', 'diskon')
                    ->get();
            $tabel = ceil($detil->count() / 12);

            if($tabel > 1) {
                for($j = 1; $j < $tabel; $j++) {
                    $newItem = collect([
                        'id' => $item->first()->id.'Z',
                        'tgl_so' => $item->first()->tgl_so,
                        'tgl_kirim' => $item->first()->tgl_kirim,
                        'total' => $item->first()->total,
                        'diskon' => $item->first()->diskon,
                        'kategori' => $item->first()->kategori,
                        'tempo' => $item->first()->tempo,
                        'id_customer' => $item->first()->id_customer,
                        'id_user' => $item->first()->id_user,
                    ]);

                    $items->push($newItem);
                }
            }
        }   

        $items = $items->sortBy(function ($product, $key) {
                    return $product['tgl_so'].$product['id'];
                });
        $items = $items->values();

        // return response()->json($items);

        $today = Carbon::now()->isoFormat('dddd, D MMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.penjualan.cetakfaktur.cetakInv', $data);
        // return view('pages.penjualan.cetakfaktur.cetakPdf', $data);

        // $paper = array(0,0,686,394);
        // $pdf = PDF::loadview('pages.penjualan.cetakfaktur.cetak', $data)->setPaper($paper);
        // ob_end_clean();
        // return $pdf->stream('cetak-all.pdf');
    } 

    public function tandaterima($awal, $akhir) {
        $items = SalesOrder::whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                ->whereBetween('id', [$awal, $akhir])->get();

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

            $app = Approval::where('id_dokumen', $item->id)->latest()->get();
            if($app->count() != 0) {
                foreach($app as $a) {
                    $a->baca = 'T';
                    $a->save();
                }
            }
        }

        $data = [
            'status' => 'false',
            'awal' => 0,
            'akhir' => 0
        ];

        return redirect()->route('cetak-faktur', $data);
    }
}
