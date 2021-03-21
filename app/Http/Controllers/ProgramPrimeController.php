<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\JenisBarang;
use App\Models\DetilSO;
use App\Models\SalesOrder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrimeNowExport;
use App\Exports\PrimeFilterExport;
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
                ->groupBy('customer.nama')->orderBy('customer.nama')->get();

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
            $sales = SalesOrder::select('id_sales as id')
                    ->where('id_customer', $request->kode)
                    ->groupBy('id_sales')->get();
        }

        $date = Carbon::now('+07:00');
        $tahun = $date->year;
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

        $customerAll = DetilSO::join('so', 'so.id', 'detilso.id_so')
                        ->join('customer', 'customer.id', 'so.id_customer')
                        ->join('barang', 'barang.id', 'detilso.id_barang')
                        ->select('customer.nama', 'customer.id as id')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->where('id_kategori', 'KAT08')->whereYear('tgl_so', $tahun)
                        ->groupBy('customer.nama')->orderBy('customer.nama')->get();

        $data = [
            'tahun' => $tahun,
            'month' => $month,
            'bulanNow' => $bulanNow,
            'bulan' => $bulan,
            'year' => $year,
            'customerAll' => $customerAll,
            'bul' => $request->bulan,
            'cust' => $request->customer,
            'id' => $request->kode,
            'customer' => $customer,
            'sales' => $sales
        ];

        return view('pages.prime.show', $data);
    }

    public function excel() {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $bulanNow = Carbon::parse($date)->isoFormat('MMMM'); 

        return Excel::download(new PrimeNowExport(), 'Prog-Prime-'.$bulanNow.'-'.$tahun.'.xlsx');
    }

    public function excelFilter(Request $request) {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $bulNow = $date->month;

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        if($request->bulan == '') {
            $month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            $bulanNow = 'Januari'.($bulNow == 'Januari' ? '' : ' - '.$bulan[$bulNow-1]);
        } else {
            for($i = 0; $i < sizeof($bulan); $i++) {
                if($request->bulan == $bulan[$i]) {
                    $month[0] = $i+1;
                    $bulanNow = $bulan[$i];
                    break;
                }
                else
                    $month[0] = '';
            }
        } 
        
        $kode = ($request->kode == '' ? 'KOSONG' : $request->kode);
        $mo = ($request->bulan == '' ? 'KOSONG' : $request->bulan);
        $bul = substr($request->bulan, 0, 3);

        return Excel::download(new PrimeFilterExport($month, $kode, $mo), 'Prog-Prime-'.$bulanNow.'-'.$tahun.'.xlsx');
    }
}
