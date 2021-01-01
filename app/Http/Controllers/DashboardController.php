<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\AR_Retur;
use App\Models\AccPayable;
use App\Models\DetilAP;
use App\Models\NeedApproval;
use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\AP_Retur;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $tanggal = Carbon::now();
        $tahun = $tanggal->year;
        $bulan = $tanggal->month;
        $tanggal = $tanggal->toDateString();
        $salesAnnual = SalesOrder::selectRaw('sum(total) as sales')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereYear('tgl_so', $tahun)->get();
        $salesMonthly = SalesOrder::selectRaw('sum(total) as sales')
                        ->whereMonth('tgl_so', $bulan)
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])->get();
        $transAnnual = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereYear('tgl_so', $tahun)->count();
        $transMonthly = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereMonth('tgl_so', $bulan)->count();
        $buyAnnual = BarangMasuk::where('status', '!=', 'BATAL')
                    ->whereYear('tanggal', $tahun)->count();
        $buyMonthly = BarangMasuk::where('status', '!=', 'BATAL')
                    ->whereMonth('tanggal', $bulan)->count();
        $retur = AR_Retur::selectRaw('sum(total) as total')
                ->whereYear('tanggal', $tahun)->get();
        $returMon = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                ->join('so', 'so.id', 'ar.id_so')
                ->selectRaw('sum(ar_retur.total) as total')
                ->whereMonth('tgl_so', $bulan)->get();
        $cicil = DetilAR::join('ar', 'ar.id', 'detilar.id_ar')
                ->join('so', 'so.id', 'ar.id_so')
                ->selectRaw('sum(cicil) as total')
                ->whereYear('tgl_so', $tahun)->get();
        $receivable = $salesAnnual[0]->sales - $retur[0]->total - $cicil[0]->total;
        $salesAnnual[0]->sales -= $retur[0]->total;
        $salesMonthly[0]->sales -= $returMon[0]->total;

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

        // $salesPerType = SalesOrder::selectRaw('kategori, count(kategori) as total')
        //                 ->whereNotIn('status', ['BATAL', 'LIMIT'])->whereYear('tgl_so', $tahun)
        //                 ->groupBy('kategori')->get();

        $tipeCount = [];
        $tipe = ['CASH', 'EXTRANA', 'PRIME'];
        for($i = 0; $i < sizeof($tipe); $i++) {
            $salesPerType = SalesOrder::where('kategori', $tipe[$i])
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereYear('tgl_so', $tahun)->count();
            $tipeCount[$i] = $salesPerType;
        }

        $needPrint = SalesOrder::whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                    ->count();
        $stillPending = NeedApproval::where('tipe', 'Faktur')->count();
        $barang = Barang::All(); 
        $restok = 0;
        foreach($barang as $b) {
            $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                    ->selectRaw('id_barang, sum(stok) as total')
                    ->where('id_barang', $b->id)->where('tipe', 'BIASA')->get();
            if($stok[0]->total <= $b->subjenis->limit) 
                $restok++;
        }   

        $fakturCount = [];
        $status = ['APPROVE_LIMIT', 'BATAL', 'CETAK', 'INPUT', 'UPDATE', 'LIMIT'];
        for($i = 0; $i < sizeof($status); $i++) {
            $fakturPerStatus = SalesOrder::where('status', $status[$i])
                        ->whereYear('tgl_so', $tahun)->count();
            $fakturCount[$i] = $fakturPerStatus;
        }
        // $fakturPerStatus = SalesOrder::selectRaw('status, count(status) as total')
        //                 ->whereYear('tgl_so', $tahun)->groupBy('status')->get();
        $lastTrans = SalesOrder::latest()->take(6)->get();
        foreach($lastTrans as $l) {
            $totalQty = DetilSO::selectRaw('sum(qty) as qty')->where('id_so', $l->id)->get();
            $l->{'qty'} = $totalQty[0]->qty;
            $l->tgl_so = Carbon::parse($l->tgl_so)->format('d-M-y');
        }
        
        $receivCount = AccReceivable::where('keterangan', 'BELUM LUNAS')->count();
        $receivTempo = AccReceivable::join('so', 'so.id', 'ar.id_so')
                        ->whereRaw('tgl_so + interval tempo day <= ?', [$tanggal])
                        ->where('keterangan', 'BELUM LUNAS')->count();
        
        $q1 = 0; $q2 = 0; $q3 = 0; $q4 = 0;
        $receiv = SalesOrder::join('ar', 'ar.id_so', 'so.id') 
                ->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('keterangan', 'BELUM LUNAS')->get();
        foreach($receiv as $s) {
            $detil = DetilAR::selectRaw('sum(cicil) as total')
                    ->where('id_ar', $s->id)->get();
            $retur = AR_Retur::selectRaw('sum(total) as total')
                    ->where('id_ar', $s->id)->get();
            $s->total -= $retur[0]->total;
            $s->{'cicil'} = $detil[0]->total;
            $total = round(($detil[0]->total * 100) / ($s->total - $s->retur), 2);
            $piutang = $s->total - $s->retur - $detil[0]->total;
            $s->{'piutang'} = $piutang;
            if($total <= 25) {
                $q1++;
            }
            elseif(($total > 25) && ($total <= 50)) {
                $q2++;
            }
            elseif(($total > 50) && ($total <= 75)) {
                $q3++;
            }
            elseif(($total > 75) && ($total <= 100)) {
                $q4++;
            }
        }

        $barCicil = [$q1, $q2, $q3, $q4];
        $bigReceiv = $receiv->sortByDesc('piutang')->take(6);
        // return response()->json($br);

        $payableCount = AccPayable::where('keterangan', 'BELUM LUNAS')->count();
        $tagihanAnnual = BarangMasuk::selectRaw('sum(total) as sales')
                        ->whereNotIn('status', ['BATAL'])->get();
        $bayar = DetilAP::selectRaw('sum(transfer) as total')->get();
        $retur = AP_Retur::selectRaw('sum(total) as total')->get();
        $payable = $tagihanAnnual[0]->sales - $bayar[0]->total - $retur[0]->total;
        $payableDiskon = BarangMasuk::where('diskon', 'F')->count();

        $q1 = 0; $q2 = 0; $q3 = 0; $q4 = 0;
        $tagihan = BarangMasuk::join('ap', 'ap.id_bm', 'barangmasuk.id_faktur') 
                ->select('barangmasuk.*')
                ->selectRaw('sum(total) as total')
                ->where('status', '!=', 'BATAL')
                ->where('keterangan', 'BELUM LUNAS')
                ->groupBy('id_faktur')->get();

        // return response()->json($tagihan);
        foreach($tagihan as $t) {
            $detil = DetilAP::selectRaw('sum(transfer) as total')
                    ->where('id_ap', $t->ap->id)->get();
            $retur = AP_Retur::selectRaw('sum(total) as total')
                    ->where('id_ap', $t->ap->id)->get();
            $t->total -= $retur[0]->total;
            $t->{'transfer'} = $detil[0]->total;
            $total = round(($detil[0]->total * 100) / $t->total, 2);
            $utang = $t->total - $detil[0]->total;
            $t->{'tagihan'} = $utang;
            if($total <= 25) {
                $q1++;
            }
            elseif(($total > 25) && ($total <= 50)) {
                $q2++;
            }
            elseif(($total > 50) && ($total <= 75)) {
                $q3++;
            }
            elseif(($total > 75) && ($total <= 100)) {
                $q4++;
            }
        }

        $barBayar = [$q1, $q2, $q3, $q4];
        $bigTagihan = $tagihan->sortByDesc('tagihan')->take(6);

        $salesAnnOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                        ->selectRaw('sum(total) as sales')
                        ->where('customer.id_sales', 'SLS03')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereYear('tgl_so', $tahun)->get();
        $salesMonOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                        ->selectRaw('sum(total) as sales')
                        ->where('customer.id_sales', 'SLS03')
                        ->whereMonth('tgl_so', $bulan)
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])->get();
        $transAnnOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                        ->where('customer.id_sales', 'SLS03')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereYear('tgl_so', $tahun)->count();
        $transMonOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                        ->where('customer.id_sales', 'SLS03')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereMonth('tgl_so', $bulan)->count();
        $returOff = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                    ->join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->selectRaw('sum(ar_retur.total) as total')
                    ->where('id_sales', 'SLS03')
                    ->whereYear('tanggal', $tahun)->get();
        $returMonOff = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                    ->join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->selectRaw('sum(ar_retur.total) as total')
                    ->where('id_sales', 'SLS03')
                    ->whereMonth('tgl_so', $bulan)->get();
        $cicilOff = DetilAR::join('ar', 'ar.id', 'detilar.id_ar')
                    ->join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->selectRaw('sum(cicil) as total')
                    ->where('id_sales', 'SLS03')
                    ->whereYear('tgl_so', $tahun)->get();
        $receivableOff = $salesAnnOff[0]->sales - $returOff[0]->total - $cicilOff[0]->total;
        $salesAnnOff[0]->sales -= $returOff[0]->total;
        $salesMonOff[0]->sales -= $returMonOff[0]->total;

        $salesPerMonOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                        ->selectRaw('sum(total) as sales, MONTH(tgl_so) month')
                        ->where('customer.id_sales', 'SLS03')
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])->whereYear('tgl_so', $tahun)
                        ->groupBy('month')->get();

        $arrTotalOff = []; $j = 0;               
        for($i = 1; $i <= 12; $i++) {
            if(($j < $salesPerMonOff->count()) && ($salesPerMonOff[$j]->month == $i)) {
                $int = (int) $salesPerMonOff[$j]->sales;
                array_push($arrTotalOff, $int);
                $j++;
            } else {
                array_push($arrTotalOff, 0);
            }
        }

        // $salesPerTypeOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
        //                 ->selectRaw('kategori, count(kategori) as total')
        //                 ->where('customer.id_sales', 'SLS03')
        //                 ->whereNotIn('status', ['BATAL', 'LIMIT'])->whereYear('tgl_so', $tahun)
        //                 ->groupBy('kategori')->get();
        
        $tipeCountOff = [];
        for($i = 0; $i < sizeof($tipe); $i++) {
            $salesPerTypeOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                        ->where('customer.id_sales', 'SLS03')
                        ->where('kategori', $tipe[$i])
                        ->whereNotIn('status', ['BATAL', 'LIMIT'])
                        ->whereYear('tgl_so', $tahun)->count();
            $tipeCountOff[$i] = $salesPerTypeOff;
        }

        $data = [
            'salesAnnual' => $salesAnnual,
            'salesMonthly' => $salesMonthly,
            'transAnnual' => $transAnnual,
            'transMonthly' => $transMonthly,
            'buyAnnual' => $buyAnnual,
            'buyMonthly' => $buyMonthly,
            'receivable' => $receivable,
            'salesPerMonth' => $salesPerMonth,
            'arrTotal' => $arrTotal,
            'salesPerType' => $tipeCount,
            'needPrint' => $needPrint,
            'stillPending' => $stillPending,
            'restock' => $restok,
            'fakturPerStatus' => $fakturCount,
            'lastTrans' => $lastTrans,
            'receivCount' => $receivCount,
            'receivTempo' => $receivTempo,
            'receiv' => $receiv,
            'barCicil' => $barCicil,
            'bigReceiv' => $bigReceiv,
            'payableCount' => $payableCount,
            'payable' => $payable,
            'payableDiskon' => $payableDiskon,
            'barBayar' => $barBayar,
            'bigTagihan' => $bigTagihan,
            'salesAnnOff' => $salesAnnOff,
            'salesMonOff' => $salesMonOff,
            'transAnnOff' => $transAnnOff,
            'transMonOff' => $transMonOff,
            'receivableOff' => $receivableOff,
            'arrTotalOff' => $arrTotalOff,
            'salesPerTypeOff' => $tipeCountOff,
        ];

        return view('pages.dashboard', $data);
    }
}
