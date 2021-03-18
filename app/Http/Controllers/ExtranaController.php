<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetilSO;
use Carbon\Carbon;

class ExtranaController extends Controller
{
    public function index() {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $month = $date->month;
        $bulan = Carbon::parse($date)->isoFormat('MMMM');
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('sales.nama as sales', 'customer.nama as cust', 'id_barang')
                ->selectRaw('sum(qty) as qty, avg(harga) as harga, sum(diskonRp) as diskonRp')
                ->where('kategori', 'LIKE', 'Extrana%')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $month)
                ->groupBy('id_customer', 'id_barang')->orderBy('id_sales')
                ->orderBy('customer.nama')->get();

        // $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
        //         ->join('customer', 'customer.id', 'so.id_customer')
        //         ->join('sales', 'sales.id', 'customer.id_sales')
        //         ->select('sales.nama as sales', 'customer.nama as cust', 'id_so as id', 'tgl_so', 'id_barang')
        //         ->selectRaw('sum(qty) as qty, avg(harga) as harga, sum(diskonRp) as diskonRp')
        //         ->where('kategori', 'LIKE', 'Extrana%')->whereNotIn('status', ['BATAL', 'LIMIT'])
        //         ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $month)
        //         ->groupBy('id_so', 'id_barang')->orderBy('id_sales')
        //         ->orderBy('customer.nama')->orderBy('tgl_so')->get();

        $data = [
            'items' => $items,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'waktu' => $waktu
        ];

        return view('pages.laporan.extrana.index', $data);
    }
}
