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
        $data = [
            'itemsBRG' => $itemsBRG,
            'rowBM' => $rowBM,
            'rowSO' => $rowSO,
            'awal' => $awal,
            'akhir' => $akhir
        ];
        
        // var_dump($itemsBRG->count());
        return view('pages.laporan.detilKS', $data);
    }
}
