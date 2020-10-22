<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\Barang;
use App\Models\DetilBM;
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

        $qtySalesPerItems = DetilSO::join('barang', 'barang.id', '=', 'detilso.id_barang')
                    ->join('so', 'so.id', '=', 'detilso.id_so')
                    ->join('customer', 'customer.id', '=', 'so.id_customer')
                    ->select('id_sales', 'id_barang', 'id_kategori', DB::raw('sum(qty) as qtyItems'), DB::raw('sum(harga * qty - diskonRp) as total')) 
                    ->whereYear('so.tgl_so', $request->tahun)
                    ->whereMonth('so.tgl_so', $month)
                    ->groupBy('id_sales', 'id_barang')
                    ->get();

        $hppPerItems = DetilBM::select('id_barang', DB::raw('avg(hpp) as avgHpp')) 
                    ->where('diskon', '!=', NULL)
                    ->whereYear('updated_at', $request->tahun)
                    ->whereMonth('updated_at', $month)
                    ->groupBy('id_barang')
                    ->get();

        foreach($qtySalesPerItems as $q) {
            foreach($hppPerItems as $h) {
                if($q->id_barang == $h->id_barang) {
                    $q['hpp'] = (int) ($q->qtyItems * $h->avgHpp);
                }
            }
        }

        // echo "<br>";
        // foreach($qtySalesPerItems as $qty) {
        // echo "<br>";
        // var_dump($qty->id_sales." = ".$qty->id_barang." = ".$qty->id_kategori." = ".$qty->qtyItems." = ".$qty->hpp);
        // }

        $items = DetilSO::join('barang', 'barang.id', '=', 'detilso.id_barang')
                    ->join('so', 'so.id', '=', 'detilso.id_so')
                    ->join('customer', 'customer.id', '=', 'so.id_customer')
                    ->join('sales', 'sales.id' , '=', 'customer.id_sales')
                    ->select('customer.id_sales', 'barang.id_kategori', DB::raw('sum(harga * qty - diskonRp) as total')) 
                    ->whereYear('so.tgl_so', $request->tahun)
                    ->whereMonth('so.tgl_so', $month)
                    ->groupBy('customer.id_sales', 'barang.id_kategori')
                    ->get();

        foreach($items as $i) {
            foreach($qtySalesPerItems as $q) {
                if(($i->id_kategori == $q->id_kategori) && ($i->id_sales == $q->id_sales)) {
                    $i['hpp'] += (int) ($q->hpp);
                }
            }
        }
        // return response()->json($items);

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
