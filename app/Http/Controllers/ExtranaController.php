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
                ->join('sales', 'sales.id', 'so.id_sales')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->select('sales.nama as sales', 'customer.nama as cust', 'id_barang', 'harga')
                ->selectRaw('sum(qty) as qty, sum(diskonRp) as diskonRp')
                ->where('id_kategori', 'KAT03')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $month)
                ->groupBy('id_customer', 'id_barang', 'harga')->orderBy('so.id_sales')
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
            'waktu' => $waktu,
            'bul' => NULL
        ];

        return view('pages.laporan.extrana.index', $data);
    }

    public function show(Request $request) {
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

        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $bulan = $request->bulan;
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'so.id_sales')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->select('sales.nama as sales', 'customer.nama as cust', 'id_barang', 'harga')
                ->selectRaw('sum(qty) as qty, sum(diskonRp) as diskonRp')
                ->where('id_kategori', 'KAT03')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $month)
                ->groupBy('id_customer', 'id_barang', 'harga')->orderBy('so.id_sales')
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
            'waktu' => $waktu,
            'bul' => $request->bulan
        ];

        return view('pages.laporan.extrana.index', $data);
    }
}
