<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\JenisBarang;
use App\Models\DetilSO;
use Carbon\Carbon;

class RekapSalesPrimeController extends Controller
{
    public function index() {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $month = $date->month;
        $bulan = Carbon::parse($date)->isoFormat('MMMM');
        $year = Carbon::parse($date)->isoFormat('Y');
        $sales = Sales::All();
        $jenis = JenisBarang::All();

        $items = DetilSO::join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->select('id_sales', 'id_customer', 'id_barang')->selectRaw('sum(qty) as qty')
                ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $month)
                ->groupBy('id_sales', 'id_customer', 'id_barang')->orderBy('id_sales')->get();
        // return response()->json($items);

        $data = [
            'tahun' => $tahun,
            'month' => $month,
            'bulan' => $bulan,
            'year' => $year,
            'sales' => $sales,
            'jenis' => $jenis
        ];

        return view('pages.laporan.rekapqtysales.index', $data);
    }

    public function show(Request $request) {
        if($request->kode == '')
            $sales = Sales::All();
        else
            $sales = Sales::where('id', $request->kode)->get();

        $jenisAll = JenisBarang::All();
        $jenis = [];
        if($request->kategori == '') {
            foreach($jenisAll as $j) {
                array_push($jenis, $j->id);
            }
        }
        else
            array_push($jenis, $request->jenis);

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
            'sales' => $sales,
            'jenisAll' => $jenisAll,
            'jenis' => $jenis,
            'bul' => $request->bulan,
            'sal' => $request->sales,
            'id' => $request->kode,
            'jen' => $request->kategori,
            'idJen' => $request->jenis
        ];

        return view('pages.laporan.rekapqtysales.show', $data);
    }
}
