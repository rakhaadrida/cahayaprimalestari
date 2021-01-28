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

        /* $itemsBM = \App\Models\DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                    ->select('id', 'id_bm', 'id_barang', 'tanggal', 'barangmasuk.created_at', 'barangmasuk.updated_at', 'detilbm.diskon as id_asal', 'disPersen as id_tujuan', 'id_user', 'qty')
                    ->where('id_barang', 'BRG0012')
                    ->whereHas('bm', function($q) {
                        $q->whereBetween('tanggal', ['2021-01-11', '2021-01-28'])
                        ->where('status', '!=', 'BATAL');
                    });
        $itemsSO = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->select('id', 'id_so', 'id_barang', 'tgl_so as tanggal', 'so.created_at', 'so.updated_at', 'detilso.diskon as id_asal', 'diskonRp as id_tujuan', 'id_user')->selectRaw('sum(qty) as qty')->where('id_barang', 'BRG0012')
                    ->whereHas('so', function($q) {
                        $q->whereBetween('tgl_so', ['2021-01-11', '2021-01-28'])
                        ->where('status', '!=', 'BATAL');
                    })->groupBy('id_so', 'id_barang');
        $items = \App\Models\DetilTB::join('transferbarang', 'transferbarang.id', 'detiltb.id_tb')
                    ->select('id', 'id_tb', 'id_barang', 'tgl_tb as tanggal', 'transferbarang.created_at', 'transferbarang.updated_at', 'id_asal', 'id_tujuan', 'id_user', 'qty')->where('id_barang', 'BRG0012')
                    ->whereHas('tb', function($q) {
                        $q->whereBetween('tgl_tb', ['2021-01-11', '2021-01-28']);
                    })->union($itemsBM)->union($itemsSO)->orderBy('created_at')->get();
        
        return response()->json($items); */
        
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
                        $q->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
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
