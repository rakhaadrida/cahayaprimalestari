<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetilSO;
use Carbon\Carbon;

class RekapSalesPrimeController extends Controller
{
    public function index() {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $month = $date->month;

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->select('id_sales', 'id_customer', 'id_barang')->selectRaw('sum(qty) as qty')
                ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $month)
                ->groupBy('id_sales', 'id_customer', 'id_barang')->orderBy('id_sales')->get();
        return response()->json($items);

        // return view('pages.laporan.prime.index');
    }
}
