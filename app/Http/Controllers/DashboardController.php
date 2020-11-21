<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\DetilSO;
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
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])->get();
        $salesMonthly = SalesOrder::selectRaw('sum(total) as sales')
                        ->whereMonth('tgl_so', $bulan)
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])->get();
        $transAnnual = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])->count();
        $transMonthly = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->where('tgl_so', $bulan)->count();
        $retur = AccReceivable::selectRaw('sum(retur) as total')->get();
        $cicil = DetilAR::selectRaw('sum(cicil) as total')->get();
        $receivable = $salesAnnual[0]->sales - $retur[0]->total - $cicil[0]->total;

        $salesPerMonth = SalesOrder::selectRaw('sum(total) as sales, MONTH(tgl_so) month')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])->whereYear('tgl_so', $tahun)
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
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])->whereYear('tgl_so', $tahun)
                        ->groupBy('kategori')->get();
        $needPrint = SalesOrder::whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                    ->count();
        $stillPending = NeedApproval::where('tipe', 'Faktur')->count();

        $fakturPerStatus = SalesOrder::selectRaw('status, count(status) as total')
                        ->whereYear('tgl_so', $tahun)->groupBy('status')->get();
        $lastTrans = SalesOrder::latest()->take(6)->get();
        foreach($lastTrans as $l) {
            $totalQty = DetilSO::selectRaw('sum(qty) as qty')->where('id_so', $l->id)->get();
            $l->{'qty'} = $totalQty[0]->qty;
            $l->tgl_so = Carbon::parse($l->tgl_so)->format('d-M-y');
        }
        // return response()->json($lastTrans);

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
            'stillPending' => $stillPending,
            'fakturPerStatus' => $fakturPerStatus,
            'lastTrans' => $lastTrans
        ];

        return view('pages.dashboard', $data);
    }
}
