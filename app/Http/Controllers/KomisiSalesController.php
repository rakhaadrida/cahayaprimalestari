<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AccReceivable;
use App\Models\Komisi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KomisiNowExport;
use App\Exports\KomisiFilterExport;
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
                ->orderBy('ar.created_at', 'desc')->get();

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
        if($request->kategori == 'ALL')  {
            $status[0] = 'EXTRANA';
            $status[1] = 'PRIME';
        }
        else {
            $status[0] = ($request->status == 'EXTRANA' ? 'EXTRANA' : '');
            $status[1] = ($request->status == 'PRIME' ? 'PRIME' : '');
        }

        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $bulNow = $date->month;
        $waktu = Carbon::now('+07:00');

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        for($i = 0; $i < sizeof($bulan); $i++) {
            if($request->bulan == $bulan[$i]) {
                $month = $i+1;
                $lastMo = ($month == 1 ? 12 : $month-1);
                $lastYear = ($month == 1 ? $date->subYear(1)->format('Y') : $tahun);
                break;
            }
            else {
                $month = $bulNow;
                $lastMo = ($bulNow == 1 ? 12 : $bulNow-1);
                $lastYear = ($bulNow == 1 ? $tahun-1 : $tahun);
            }
        }

        $tanggal = $tahun.'-'.$month;
        $lastTanggal = $lastYear.'-'.$lastMo;

        $monthNow = Carbon::parse($tanggal)->format('Y-m-20');
        $bulanNow = Carbon::parse($tanggal)->isoFormat('MMMM'); 
        $lastMonth = Carbon::parse($lastTanggal)->format('Y-m-21');
        $bulanLast = Carbon::parse($lastTanggal)->isoFormat('MMMM');

        if($request->kategori == 'ALL') {
            $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id', 'customer.id_sales')
                    ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                    ->where('kategori', 'NOT LIKE', $status[0].'%')
                    ->where('kategori', 'NOT LIKE', $status[1].'%')
                    ->where('id_sales', 'SLS12')
                    ->orderBy('ar.created_at', 'desc')->get();
        } else {
            $ar = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->join('sales', 'sales.id', 'customer.id_sales')
                ->select('ar.id as id', 'ar.*', 'id_so', 'id_sales')
                ->where('id_sales', 'SLS12')
                ->where('kategori', 'LIKE', $request->kategori.'%')
                ->orderBy('ar.created_at', 'desc')->get();
        }
                
        $data = [
            'ar' => $ar,
            'bulan' => $request->bulan,
            'status' => $request->status,
            'monthNow' => $monthNow,
            'lastMonth' => $lastMonth,
            'bulanNow' => $bulanNow,
            'bulanLast' => $bulanLast,
            'kat' => $request->kategori
        ];

        return view('pages.komisi.show', $data);
    }

    public function excel() {
        $date = Carbon::now('+07:00');
        $bulanNow = Carbon::parse($date)->isoFormat('MMMM'); 
        $lastMonth = $date->subMonths(1)->format('Y-m-21');
        $bulanLast = Carbon::parse($date)->isoFormat('MMM');

        return Excel::download(new KomisiNowExport(), 'Komisi-Fadil-'.$bulanNow.'.xlsx');
    }

    public function excelFilter(Request $request) {
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
            else {
                $month = $bulNow;
                $lastMo = ($bulNow == 1 ? 12 : $bulNow-1);
                $lastYear = ($bulNow == 1 ? $tahun-1 : $tahun);
            }
        }

        $tanggal = $tahun.'-'.$month;
        $lastTanggal = $lastYear.'-'.$lastMo;

        $monthNow = Carbon::parse($tanggal)->format('Y-m-20');
        $bulanNow = Carbon::parse($tanggal)->isoFormat('MMM'); 
        $lastMonth = Carbon::parse($lastTanggal)->format('Y-m-21');
        $bulanLast = Carbon::parse($lastTanggal)->isoFormat('MMM');
        
        $kategori = $request->kategori;

        return Excel::download(new KomisiFilterExport($month, $kategori, $tanggal, $lastTanggal), 'Komisi-Fadil-'.$bulanNow.'-'.$request->kategori.'.xlsx');
    }

    public function upload() {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;

        $data = [
            'tahun' => $tahun
        ];

        return view('pages.komisi.upload', $data);
    }

    public function storeUpload(Request $request) {
        for($i = 0; $i < 12; $i++) {
            if($request->file($i) != '') {
                $nama = $request->file($i)->getClientOriginalName();
                $item = Komisi::where('bulan', $i+1)->first();
                if($item == NULL) {
                    Komisi::create([
                        'bulan' => $i+1,
                        'tanggal' => Carbon::now('+07:00')->toDateString(),
                        'file' => $request->file($i)->storeAs('assets/komisi', $nama)
                    ]);
                } else {
                    Storage::delete($item->{'file'});
                    $item->{'tanggal'} = Carbon::now('+07:00')->toDateString();
                    $item->{'file'} = $request->file($i)->storeAs('assets/komisi', $nama);
                    $item->save();
                }
            }
        }

        return redirect()->route('komisi-upload');
    }

    public function download($path) {
        $item = Komisi::where('bulan', $path)->first();
        return response()->download(storage_path("app/".$item->{'file'}));
    }
}