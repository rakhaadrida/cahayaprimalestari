<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\NeedApproval;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $tanggal = Carbon::now();
        $tahun = $tanggal->year;
        $bulan = $tanggal->month;
        $salesAnnual = SalesOrder::selectRaw('sum(total) as sales')
                        ->where('status', '!=', 'BATAL')->get();
        $salesMonthly = SalesOrder::selectRaw('sum(total) as sales')
                        ->whereMonth('tgl_so', $bulan)
                        ->where('status', '!=', 'BATAL')->get();
        $transAnnual = SalesOrder::where('status', '!=', 'BATAL')->count();
        $transMonthly = SalesOrder::where('status', '!=', 'BATAL')
                        ->where('tgl_so', $bulan)->count();
        $retur = AccReceivable::selectRaw('sum(retur) as total')->get();
        $cicil = DetilAR::selectRaw('sum(cicil) as total')->get();
        $receivable = $salesAnnual[0]->sales - $retur[0]->total - $cicil[0]->total;

        $salesPerMonth = SalesOrder::selectRaw('sum(total) as sales, MONTH(tgl_so) month')
                        ->where('status', '!=', 'BATAL')->whereYear('tgl_so', $tahun)
                        ->groupBy('month')->get();

        $arrTotal = []; $j = 0;               
        for($i = 1; $i <= 12; $i++) {
            if(($j < $salesPerMonth->count()) && ($salesPerMonth[$j]->month == $i)) {
                $int = (int) $salesPerMonth[$j]->sales;
                array_push($arrTotal, $int);
                $j++;
            } else {
                array_push($arrTotal, 0);
            }
        }

        $salesPerType = SalesOrder::selectRaw('kategori, count(kategori) as total')
                        ->where('status', '!=', 'BATAL')->whereYear('tgl_so', $tahun)
                        ->groupBy('kategori')->get();
        $needPrint = SalesOrder::whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                    ->count();
        $stillPending = NeedApproval::where('tipe', 'Faktur')->count();

        $data = [
            'salesAnnual' => $salesAnnual,
            'salesMonthly' => $salesMonthly,
            'transAnnual' => $transAnnual,
            'transMonthly' => $transMonthly,
            'receivable' => $receivable,
            'salesPerMonth' => $salesPerMonth,
            'arrTotal' => $arrTotal,
            'salesPerType' => $salesPerType,
            'needPrint' => $needPrint,
            'stillPending' => $stillPending
        ];

        return view('pages.dashboard', $data);
    }
}
