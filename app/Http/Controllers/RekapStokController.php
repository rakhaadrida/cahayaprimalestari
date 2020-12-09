<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Subjenis;
use App\Models\DetilBM;
use App\Models\DetilSO;
use Illuminate\Support\Facades\DB;
use PDF;
// use PDFSnappy;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapStokExport;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class RekapStokController extends Controller
{
    public function index() {
        $jenis = JenisBarang::All();
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();

        // return response()->json($jenis);

        $data = [
            'jenis' => $jenis,
            'gudang' => $gudang,
            'stok' => $stok,
        ];
        
        return view('pages.laporan.rekapstok.index', $data);
    }

    public function show(Request $request) {
        $awal = $request->tanggal;
        $akhir = Carbon::now('+07:00')->format('Y-m-d');
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
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
            'awal' => $awal,
            'stokAwal' => $stokAwal,
            'gudang' => $gudang,
            'stok' => $stok,
        ];

        return view('pages.laporan.rekapstok.index', $data);
    }

    public function cetak() {
        $jenis = JenisBarang::All();
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');

        $data = [
            'jenis' => $jenis,
            'gudang' => $gudang,
            'stok' => $stok,
            'waktu' => $waktu
        ];

        return view('pages.laporan.rekapstok.cetak', $data);
    }

    public function cetak_pdf() {
        $jenis = JenisBarang::All();
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $waktu = Carbon::now('+07:00');
        $waktu = $waktu->format('d F Y, H:i:s');

        /* $kode = []; $baris = 1;
        $sub = Subjenis::All();
        foreach($sub as $s) {
            if($baris <= 3) {
                $barang = Barang::where('id_sub', $s->id)->get();
                if(($barang->count() + 1) <= 3) {
                    $baris++;
                    array_push($kode, $s->id);
                    foreach($barang as $b) {
                        $baris++;
                    }
                }
            }
        }
        $subRest = Subjenis::whereNotIn('id', $kode)->get(); */

        $data = [
            'jenis' => $jenis,
            'gudang' => $gudang,
            'stok' => $stok,
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.laporan.rekapstok.pdf', $data)->setPaper('A4', 'portrait');
        // $pdf->setOption('enable-local-file-access', true);
        ob_end_clean();
        return $pdf->stream('rekap-stok.pdf');
    }

    public function cetak_excel() {
        return Excel::download(new RekapStokExport, 'rekap-stok.xlsx');
    }
}
