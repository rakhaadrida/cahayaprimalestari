<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\JenisBarang;
use App\Models\DetilSO;
use Carbon\Carbon;

class ProgramPrimeController extends Controller
{
    public function index() {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $month = $date->month;
        $bulan = Carbon::parse($date)->isoFormat('MMMM');
        $year = Carbon::parse($date)->isoFormat('Y');
        $sales = Sales::All();

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->select('customer.nama', 'customer.id as id')
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                ->whereMonth('tgl_so', $month)
                ->groupBy('customer.nama')->get();

        $data = [
            'tahun' => $tahun,
            'month' => $month,
            'bulan' => $bulan,
            'year' => $year,
            'sales' => $sales,
            'cust' => $items
        ];

        return view('pages.prime.index', $data);
    }

    public function show(Request $request) {
        if($request->kode == '') {
            $customer = Customer::All();
            $sales = Sales::All();
        } else {
            $customer = Customer::where('id', $request->kode)->get();
            $sales = Sales::join('customer', 'customer.id_sales', 'sales.id')
                    ->select('id_sales')->where('customer.id', $request->kode);
        }

        $salesAll = Sales::All();
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        // $month = $date->month;
        $bulNow = Carbon::parse($date)->isoFormat('MMMM');
        $year = Carbon::parse($date)->isoFormat('Y');

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        if($request->bulan == '') {
            $month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            $bulanNow = 'Januari'.($bulNow == 'Januari' ? '' : ' - '.$bulNow);
        } else {
            for($i = 0; $i < sizeof($bulan); $i++) {
                if($request->bulan == $bulan[$i]) {
                    $month[0] = $i+1;
                    $bulanNow = Carbon::parse($date)->isoFormat('MMMM');
                    break;
                }
                else
                    $month[0] = '';
            }
        }

        $data = [
            'tahun' => $tahun,
            'month' => $month,
            'bulanNow' => $bulanNow,
            'bulan' => $bulan,
            'year' => $year,
            'salesAll' => $salesAll,
            'bul' => $request->bulan,
            'cust' => $request->customer,
            'id' => $request->kode,
            'customer' => $customer,
            'sales' => $sales
        ];

        return view('pages.prime.show', $data);
    }
}
