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
        $now = Carbon::now('+07:00')->toDateString();
        $tglAwal = $request->tglAwal;
        $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
        $tglAkhir = $request->tglAkhir;
        $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');
        $barang = Barang::All();
        $gudang = Gudang::where('tipe', '!=', 'RETUR')->get();

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
        $stok = StokBarang::select('id_barang', DB::raw('sum(stok) as total'))
                        ->whereBetween('id_barang', [$request->kodeAwal, $request->kodeAkhir])
                        ->where('status', '!=', 'F')
                        ->groupBy('id_barang')->get();

        $i = 0;
        $stokAwal = [];
        foreach($stok as $s) {
            $stokAwal[$i] = $s->total;
            $itemsBM = \App\Models\DetilBM::selectRaw('sum(qty) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('bm', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tanggal', [$tglAwal, $now])
                            ->where('status', '!=', 'BATAL');
                        })->get();
            foreach($itemsBM as $bm) {
                $stokAwal[$i] -= $bm->qty;
            }

            $itemsSO = \App\Models\DetilSO::selectRaw('sum(qty) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('so', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tgl_so', [$tglAwal, $now])
                            ->whereNotIn('status', ['BATAL', 'LIMIT']);
                        })->get();
            foreach($itemsSO as $so) {
                $stokAwal[$i] += $so->qty;
            }

            $itemsRJ = \App\Models\DetilRJ::selectRaw('sum(qty_kirim) as qty')
//                        \App\Models\DetilRJ::selectRaw('sum(qty_retur - qty_kirim) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('retur', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tanggal', [$tglAwal, $now])
                            ->where('status', '!=', 'BATAL');
                        })->get();
            foreach($itemsRJ as $rj) {
                $stokAwal[$i] -= $rj->qty;
            }

            $itemsRB = \App\Models\DetilRB::selectRaw('sum(qty_retur) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereHas('returbeli', function($q) use($tglAwal, $now) {
                            $q->whereBetween('tanggal', [$tglAwal, $now])
                            ->where('status', '!=', 'BATAL');
                        })->get();
            foreach($itemsRB as $rb) {
                $itemsRT = \App\Models\DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                        ->join('returbeli', 'returbeli.id', 'returterima.id_retur')
                        ->selectRaw('sum(qty_terima + qty_batal) as qty')
                        ->where('id_barang', $s->id_barang)
                        ->whereBetween('returbeli.tanggal', [$tglAwal, $now])->get();

                $stokAwal[$i] += $itemsRT->first()->qty;
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
