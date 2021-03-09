<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccReceivable;
use Carbon\Carbon;

class KomisiSalesController extends Controller
{
    public function index() {
        $date = Carbon::now('+07:00');
        $monthNow = Carbon::parse($date)->format('Y-m-20');
        $bulanNow = Carbon::parse($date)->isoFormat('MMMM'); 
        $lastMonth = $date->subMonths(1)->format('Y-m-21');
        $bulanLast = Carbon::parse($date)->isoFormat('MMMM');
        
        $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                ->where('id_sales', 'SLS12')
                ->where('keterangan', 'BELUM LUNAS')
                ->orWhere(function($q) use($monthNow, $lastMonth) {
                    $q->where('keterangan', 'LUNAS')
                    ->whereBetween('ar.updated_at', [$lastMonth, $monthNow])
                    ->where('id_sales', 'SLS12');
                })->orderBy('ar.created_at', 'desc')->get();

        // return response()->json($bulanLast);

        $data = [
            'ar' => $ar,
            'monthNow' => $monthNow,
            'lastMonth' => $lastMonth,
            'bulanNow' => $bulanNow,
            'bulanLast' => $bulanLast
        ];

        return view('pages.komisi.index', $data);
    }

    public function show(Request $request) {
        if($request->status == 'ALL')  {
            $status[0] = 'LUNAS';
            $status[1] = 'BELUM LUNAS';
        }
        else {
            $status[0] = ($request->status == 'LUNAS' ? 'LUNAS' : '');
            $status[1] = ($request->status == 'BELUM LUNAS' ? 'BELUM LUNAS' : '');
        }

        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $bulNow = $date->month;

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        for($i = 0; $i < sizeof($bulan); $i++) {
            if($request->bulan == $bulan[$i]) {
                $month = $i+1;
                $lastMo = ($month == 1 ? 12 : $month-1);
                $lastYear = ($month == 1 ? $date->subYear(1)->format('Y') : $tahun);
                break;
            }
            else
                $month = '';
                $lastMo = '';
        }

        $tanggal = $tahun.'-'.$month;
        $lastTanggal = $lastYear.'-'.$lastMo;

        $monthNow = Carbon::parse($tanggal)->format('Y-m-20');
        $bulanNow = Carbon::parse($tanggal)->isoFormat('MMMM'); 
        $lastMonth = Carbon::parse($lastTanggal)->format('Y-m-21');
        $bulanLast = Carbon::parse($lastTanggal)->isoFormat('MMMM');

        if($bulNow != $month) {
            $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id', 'customer.id_sales')
                    ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                    ->where('keterangan', $status[0])
                    ->whereBetween('ar.updated_at', [$lastMonth, $monthNow])
                    ->where('id_sales', 'SLS12')
                    ->orderBy('ar.created_at', 'desc')->get();
        } else {
            $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                ->where('id_sales', 'SLS12')
                ->where('keterangan', $status[1])
                ->orWhere(function($q) use($monthNow, $lastMonth, $status) {
                    $q->where('keterangan', $status[0])
                    ->whereBetween('ar.updated_at', [$lastMonth, $monthNow])
                    ->where('id_sales', 'SLS12');
                })->orderBy('ar.created_at', 'desc')->get();
        }
                
        $data = [
            'ar' => $ar,
            'bulan' => $request->bulan,
            'status' => $request->status,
            'monthNow' => $monthNow,
            'lastMonth' => $lastMonth,
            'bulanNow' => $bulanNow,
            'bulanLast' => $bulanLast
        ];

        return view('pages.komisi.show', $data);
    }
}
