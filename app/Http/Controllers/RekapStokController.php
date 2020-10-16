<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\Barang;
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
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $data = [
            'gudang' => $gudang,
            'stok' => $stok,
        ];
        
        return view('pages.laporan.rekapStok', $data);
    }

    public function cetak() {
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $waktu = Carbon::now();
        $waktu = $waktu->format('d F Y, H:i:s');

        $data = [
            'gudang' => $gudang,
            'stok' => $stok,
            'waktu' => $waktu
        ];

        return view('pages.laporan.cetakRekap', $data);
    }

    public function cetak_pdf() {
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $waktu = Carbon::now();
        $waktu = $waktu->format('d F Y, H:i:s');

        $data = [
            'gudang' => $gudang,
            'stok' => $stok,
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.laporan.pdfRekap', $data)->setPaper('A4', 'portrait');
        // $pdf->setOption('enable-local-file-access', true);
        ob_end_clean();
        return $pdf->stream('rekap-stok.pdf');
    }

    public function cetak_excel() {

        return Excel::download(new RekapStokExport, 'rekap-stok.xlsx');
    }
}
