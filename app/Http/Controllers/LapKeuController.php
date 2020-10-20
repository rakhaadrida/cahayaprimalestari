<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\DetilSO;
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

        $i = 0;
        $custBySales = array();
        $sales = Sales::All();
        foreach($sales as $s) {
            $custBySales[$i] = array();
            $customer = Customer::where('id_sales', $s->id)->get();
            
            foreach($customer as $c) {
                array_push($custBySales[$i], $c->id);
            }
            var_dump($custBySales[$i]);
            echo "<br>";
            $i++;
        }

        $items = SalesOrder::
                    select('id_customer', DB::raw('sum(total) as total')) 
                    ->whereYear('tgl_so', $request->tahun)
                    ->whereMonth('tgl_so', $month)
                    ->whereIn('id_customer', $custBySales[2])
                    ->groupBy('id_customer')
                    ->get();
        foreach($items as $i) {
        echo "<br>";
        // var_dump(sizeof($custBySales));
        var_dump($i->id_customer." = ".$i->total);
        }

        // $jenis = JenisBarang::All();
    }
}
