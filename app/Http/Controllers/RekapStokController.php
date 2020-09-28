<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StokBarang;
use App\Gudang;
use App\Barang;
use Illuminate\Support\Facades\DB;

class RekapStokController extends Controller
{
    public function index() {
        $gudang = Gudang::All();
        $stok = StokBarang::with(['barang'])->select('id_barang', DB::raw('sum(stok) as total'))
                        ->groupBy('id_barang')->get();
        $data = [
            'gudang' => $gudang,
            'stok' => $stok
        ];

        // var_dump($stok[0]->barang);
        
        return view('pages.laporan.rekapStok', $data);
    }
}
