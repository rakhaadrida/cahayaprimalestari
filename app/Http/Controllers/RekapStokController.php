<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapStokExport;

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
        $data = [
            'gudang' => $gudang,
            'stok' => $stok,
        ];

        $pdf = PDF::loadview('pages.laporan.cetakRekap', $data)->setPaper('A4', 'portrait');
        return $pdf->stream('rekap-stok.pdf');
    }

    public function cetak_excel() {

        return Excel::download(new RekapStokExport, 'rekap-stok.xlsx');
    }
}
