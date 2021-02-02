<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;
use App\Models\BarangMasuk;
use App\Models\TransferBarang;
use App\Models\StokBarang;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\DetilBM;
use App\Models\DetilSO;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KartuStokExport;
use Carbon\Carbon;

class KartuStokController extends Controller
{
    public function index() {
        $barang = Barang::All();
        
        $data = [
            'barang' => $barang
        ];

        return view('pages.laporan.kartustok.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function show(Request $request) {
        $tglAwal = $request->tglAwal;
        $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
        $tglAkhir = $request->tglAkhir;
        $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');
        $barang = Barang::All();
        $gudang = Gudang::All();

        $rowBM = DetilBM::with(['bm', 'barang'])
                    ->whereBetween('id_barang', [$request->kodeAwal, $request->kodeAkhir])
                    ->whereHas('bm', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                        ->where('status', '!=', 'BATAL');
                    })->count();
        $rowSO = DetilSO::with(['so', 'barang'])
                    ->whereBetween('id_barang', [$request->kodeAwal, $request->kodeAkhir])
                    ->whereHas('so', function($q) use($tglAwal, $tglAkhir) {
                        $q->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                        ->where('status', '!=', 'BATAL');
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
                        ->whereHas('bm', function($q) use($tglAwal, $tglAkhir) {
                            $q->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
                        })->get();
            foreach($itemsBM as $bm) {
                $stokAwal[$i] -= $bm->qty;
            }

            $itemsSO = \App\Models\DetilSO::with(['so', 'barang'])
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('so', function($q) use($tglAwal, $tglAkhir) {
                            $q->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                            ->where('status', '!=', 'BATAL');
                        })->get();
            foreach($itemsSO as $so) {
                $stokAwal[$i] += $so->qty;
            }

            $i++;
        }

        $data = [
            'gudang' => $gudang,
            'barang' => $barang,
            'itemsBRG' => $itemsBRG,
            'rowBM' => $rowBM,
            'rowSO' => $rowSO,
            'awal' => $tglAwal,
            'akhir' => $tglAkhir,
            'stok' => $stok,
            'stokAwal' => $stokAwal
        ];
        
        return view('pages.laporan.kartustok.detail', $data);
    }

    public function cetak_excel(Request $request) {
        $awal = $request->tglAwal;
        $awal = $this->formatTanggal($awal, 'Y-m-d');
        $akhir = $request->tglAkhir;
        $akhir = $this->formatTanggal($akhir, 'Y-m-d');
        $kodeAwal = $request->kodeAwal;
        $kodeAkhir = $request->kodeAkhir;

        return Excel::download(new KartuStokExport($awal, $akhir, $kodeAwal, $kodeAkhir), 'kartu-stok.xlsx');
    }
}
