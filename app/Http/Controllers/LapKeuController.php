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
use App\Models\AccReceivable;
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

        $hppPerItems = DetilBM::join('barangmasuk', 'barangmasuk.id', '=', 'detilbm.id_bm')
                    ->select('id_barang', DB::raw('avg(harga) as avgHarga'),
                    DB::raw('avg(disPersen) as avgDisPersen')) 
                    ->where('diskon', '!=', NULL)
                    ->whereYear('barangmasuk.tanggal', $request->tahun)
                    ->whereMonth('barangmasuk.tanggal', $month)
                    ->groupBy('id_barang')
                    ->get();

        foreach($qtySalesPerItems as $q) {
            foreach($hppPerItems as $h) {
                if($q->id_barang == $h->id_barang) {
                    $q['hpp'] = number_format($h->avgDisPersen, 2, ".", "");
                    $q['hrg'] = number_format($h->avgHarga, 0, "", "");
                    $q['disHpp'] = number_format(($h->avgHarga * $h->avgDisPersen) / 100, 0, "", "");
                    $q['hrgHpp'] = number_format($h->avgHarga - $q['disHpp'], 0, "", "");
                    $q['totHpp'] = $q['hrgHpp'] * $q->qtyItems;
                }
            }
        }

        // echo "<br>";
        // foreach($qtySalesPerItems as $qty) {
        // echo "<br>";
        // var_dump($qty->id_sales." = ".$qty->id_barang." = ".$qty->id_kategori." = ".$qty->qtyItems." = ".$qty->hpp." = ".$qty->hrg." = ".$qty->disHpp." = ".$qty->hrgHpp." = ".$qty->totHpp);
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
        
        $retur = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                    ->join('customer', 'customer.id', '=', 'so.id_customer')
                    ->join('sales', 'sales.id' , '=', 'customer.id_sales')
                    ->select('customer.id_sales', DB::raw('sum(retur) as total')) 
                    ->whereYear('so.tgl_so', $request->tahun)
                    ->whereMonth('so.tgl_so', $month)
                    ->groupBy('customer.id_sales')
                    ->get();

        foreach($items as $i) {
            foreach($qtySalesPerItems as $q) {
                if(($i->id_kategori == $q->id_kategori) && ($i->id_sales == $q->id_sales)) {
                    $i['hpp'] += $q->totHpp;
                }
            }
        }
        // return response()->json($items);
        // echo "<br>";
        // foreach($items as $i) {
        // echo "<br>";
        // var_dump($i->id_sales." = ".$i->id_kategori." = ".$i->hpp);
        // }

        $data = [
            'tahun' => $tahun,
            'bulan' => $request->bulan,
            'month' => $month,
            'jenis' => $jenis,
            'sales' => $sales,
            'items' => $items,
            'retur' => $retur
        ];

        return view('pages.keuangan.show', $data);
    }
}
