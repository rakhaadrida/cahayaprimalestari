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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapStokExport;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class RekapStokController extends Controller
{
    public function index() {
        $jenis = JenisBarang::All();
        $gudang = Gudang::All();

        $data = [
            'jenis' => $jenis,
            'gudang' => $gudang,
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
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');

        foreach($jenis as $j) {
            $sub = Subjenis::where('id_kategori', $j->id)->count();
            $brg = Barang::where('id_kategori', $j->id)->count();
            $j->{'total'} = $brg + $sub;
        }

        $el = 0; $k = $jenis->count(); $gabung = 0;
        foreach($jenis as $j) {
            if($j->total <= 80) {
                for($i = $el+1; $i < $k; $i++) { 
                    if($jenis[$el]->total + $jenis[$i]->total <= 127) {
                        if($jenis[$i]->nama != 'PRIME') {
                            $jenis[$el]->total += $jenis[$i]->total;
                            $jenis[$el]->id = $jenis[$el]->id.','.$jenis[$i]->id;
                            $jenis[$el]->nama = $jenis[$el]->nama.', '.$jenis[$i]->nama;
                            $kode = $jenis[$i]->id;

                            $jenis = $jenis->filter(function($item) use($kode) {
                                return $item->id != $kode;
                            });
                        }
                        $gabung++;
                    }
                }
                $k -= $gabung;
            } 

            $el++;
        }

        $jenis = $jenis->values();

        $data = [
            'jenis' => $jenis,
            'gudang' => $gudang,
            'stok' => $stok,
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.laporan.rekapstok.pdf', $data)->setPaper('A4', 'portrait');
        ob_end_clean();
        return $pdf->stream('rekap-stok.pdf');
    }

    public function cetak_excel() {
        return Excel::download(new RekapStokExport, 'rekap-stok.xlsx');
    }
}
