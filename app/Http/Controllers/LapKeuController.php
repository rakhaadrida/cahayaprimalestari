<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class LapKeuController extends Controller
{
    public function index() {
        return view('pages.keuangan.index');
    }

    public function show(Request $request) {
        $tahun = $request->tahun;
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        for($i = 0; $i < sizeof($bulan); $i++) {
            if($request->bulan == $bulan[$i]) {
                $month = $i+1;
                break;
            }
            else
                $month = '';
        }

        $jenis = JenisBarang::All();
        $sales = Sales::All();

        // $so = SalesOrder::join('customer', 'customer.id', '=', 'so.id_customer')
        //             ->select('customer.id_sales', DB::raw('sum(total) as total')) 
        //             ->whereYear('tgl_so', $request->tahun)
        //             ->whereMonth('tgl_so', $month)
        //             ->groupBy('customer.id_sales')
        //             ->get();

        $items = DetilSO::join('barang', 'barang.id', '=', 'detilso.id_barang')
                    ->join('so', 'so.id', '=', 'detilso.id_so')
                    ->join('customer', 'customer.id', '=', 'so.id_customer')
                    ->join('sales', 'sales.id' , '=', 'customer.id_sales')
                    ->select('customer.id_sales', 'barang.id_kategori', DB::raw('sum(harga * qty) as total')) 
                    ->whereYear('so.tgl_so', $request->tahun)
                    ->whereMonth('so.tgl_so', $month)
                    ->groupBy('customer.id_sales', 'barang.id_kategori')
                    ->get();

        // echo "<br>";            
        // foreach($items as $i) {
        // echo "<br>";
        // var_dump($i->id_sales." - ".$i->id_kategori." = ".$i->total);
        // }

        $data = [
            'tahun' => $tahun,
            'bulan' => $request->bulan,
            'jenis' => $jenis,
            'sales' => $sales,
            'items' => $items
        ];

        return view('pages.keuangan.show', $data);
    }
}
