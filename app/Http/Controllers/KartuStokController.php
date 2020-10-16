<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;
use App\Models\BarangMasuk;
use App\Models\TransferBarang;
use App\Models\StokBarang;
use App\Models\Barang;
use App\Models\DetilBM;
use App\Models\DetilSO;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    public function index() {
        $barang = Barang::All();
        $data = [
            'barang' => $barang
        ];

        return view('pages.laporan.kartuStok', $data);
    }

    public function show(Request $request) {
        $awal = $request->tglAwal;
        $akhir = $request->tglAkhir;
        $barang = Barang::All();

        $rowBM = DetilBM::with(['bm', 'barang'])
                    ->whereBetween('id_barang', [$request->kodeAwal, $request->kodeAkhir])
                    ->whereHas('bm', function($q) use($awal, $akhir) {
                        $q->whereBetween('tanggal', [$awal, $akhir]);
                    })->count();
        $rowSO = DetilSO::with(['so', 'barang'])
                    ->whereBetween('id_barang', [$request->kodeAwal, $request->kodeAkhir])
                    ->whereHas('so', function($q) use($awal, $akhir) {
                        $q->whereBetween('tgl_so', [$awal, $akhir]);
                    })->count();
        $itemsBRG = Barang::whereBetween('id', [$request->kodeAwal, $request->kodeAkhir])
                        ->get();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->whereBetween('id_barang', [$request->kodeAwal, $request->kodeAkhir])
                        ->groupBy('id_barang')->get();

        $i = 0;
        $stokAwal = [];
        foreach($stok as $s) {
            $stokAwal[$i] = $s->total;
            $itemsBM = \App\Models\DetilBM::with(['bm', 'barang'])
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('bm', function($q) use($awal, $akhir) {
                            $q->whereBetween('tanggal', [$awal, $akhir]);
                        })->get();
            foreach($itemsBM as $bm) {
                $stokAwal[$i] -= $bm->qty;
            }

            $itemsSO = \App\Models\DetilSO::with(['so', 'barang'])
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('so', function($q) use($awal, $akhir) {
                            $q->whereBetween('tgl_so', [$awal, $akhir]);
                        })->get();
            foreach($itemsSO as $so) {
                $stokAwal[$i] += $so->qty;
            }

            $i++;
        }

        $data = [
            'barang' => $barang,
            'itemsBRG' => $itemsBRG,
            'rowBM' => $rowBM,
            'rowSO' => $rowSO,
            'awal' => $awal,
            'akhir' => $akhir,
            'stok' => $stok,
            'stokAwal' => $stokAwal
        ];
        
        // var_dump($stok);
        return view('pages.laporan.detilKS', $data);
    }
}
