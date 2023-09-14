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
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        set_time_limit(600);

        $tanggal = Carbon::now();
        $tahun = $tanggal->year;
        $bulan = $tanggal->month;
        $tanggal = $tanggal->toDateString();

        $tipe = ['CASH', 'EXTRANA', 'PRIME'];

        if(Auth::user()->roles == 'SUPER') {
            $salesMonthly = SalesOrder::selectRaw('sum(total) as sales')
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->where('id_customer', '!=', 'CUS1071')
                            ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $bulan)->get();
        }

        if((Auth::user()->roles == 'SUPER') || (Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'AR')) {
            $transAnnual = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                                ->whereYear('tgl_so', $tahun)->count();
            $transMonthly = SalesOrder::whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $bulan)->count();
        }

        if(Auth::user()->roles == 'KENARI') {
            $transAnnualKen = SalesOrder::join('users', 'users.id', 'so.id_user')
                            ->where('roles', 'KENARI')->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereYear('tgl_so', $tahun)->count();
            $transMonthlyKen = SalesOrder::join('users', 'users.id', 'so.id_user')
                            ->where('roles', 'KENARI')->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereMonth('tgl_so', $bulan)->count();
        }

        // $returMon = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
        //         ->join('so', 'so.id', 'ar.id_so')
        //         ->selectRaw('sum(ar_retur.total) as total')
        //         ->whereYear('tanggal', $tahun)->whereMonth('tgl_so', $bulan)->get();

        if((Auth::user()->roles == 'SUPER') || (Auth::user()->roles == 'AR')) {
            $salesAnnual = SalesOrder::selectRaw('sum(total) as sales')
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->where('id_customer', '!=', 'CUS1071')
                            ->whereYear('tgl_so', $tahun)->get();
            $retur = AR_Retur::selectRaw('sum(total) as total')
                    ->whereYear('tanggal', $tahun)->get();
            $returMon = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                    ->join('so', 'so.id', 'ar.id_so')
                    ->selectRaw('sum(ar_retur.total) as total')
                    ->whereYear('tgl_so', $tahun)->whereMonth('tgl_so', $bulan)->get();
            $cicil = DetilAR::join('ar', 'ar.id', 'detilar.id_ar')
                    ->join('so', 'so.id', 'ar.id_so')
                    ->selectRaw('sum(cicil) as total')
                    ->whereYear('tgl_so', $tahun)->get();
            $receivable = $salesAnnual[0]->sales - $retur[0]->total - $cicil[0]->total;
        }

        if(Auth::user()->roles == 'SUPER') {
            $salesAnnual[0]->sales -= $retur[0]->total;
            // $salesMonthly[0]->sales -= $returMon[0]->total;

            $salesPerMonth = SalesOrder::selectRaw('sum(total) as sales, MONTH(tgl_so) month')
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->where('id_customer', '!=', 'CUS1071')->whereYear('tgl_so', $tahun)
                            ->groupBy('month')->get();
            // return response()->json($salesPerMonth);
            $returPerMonth = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                            ->join('so', 'so.id', 'ar.id_so')
                            ->selectRaw('sum(ar_retur.total) as total, MONTH(tgl_so) month')
                            ->whereYear('tanggal', $tahun)->groupBy('month')->get();
            // return response()->json($returPerMonth);

            $arrTotal = []; $j = 0;
            for($i = 1; $i <= 12; $i++) {
                if(($j < $salesPerMonth->count()) && ($salesPerMonth[$j]->month == $i)) {
                    if(($returPerMonth->count() != 0) && ($j < $returPerMonth->count()) && ($returPerMonth[$j] != NULL))
                        // $int = (int) $salesPerMonth[$j]->sales - $returPerMonth[$j]->total;
                        $int = (int) $salesPerMonth[$j]->sales;
                    else
                        $int = $salesPerMonth[$j]->sales;

                    array_push($arrTotal, $int);
                    $j++;
                } else {
                    array_push($arrTotal, 0);
                }
            }

            $tipeCount = [];
            for($i = 0; $i < sizeof($tipe); $i++) {
                $salesPerType = SalesOrder::where('kategori', $tipe[$i])
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereYear('tgl_so', $tahun)->count();
                $tipeCount[$i] = $salesPerType;
            }
        }

        if(Auth::user()->roles == 'ADMIN') {
            $needPrint = SalesOrder::whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                        ->count();
            $stillPending = NeedApproval::where('tipe', 'Faktur')->count();
        }

        if(Auth::user()->roles == 'KENARI') {
            $needPrintKen = SalesOrder::join('users', 'users.id', 'so.id_user')
                            ->where('roles', 'KENARI')->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
                            ->count();
            $stillPendingKen = NeedApproval::join('users', 'users.id', 'need_approval.id_user')
                        ->where('roles', 'KENARI')->where('tipe', 'Faktur')->count();
        }

        $barang = Barang::All();
        $restok = 0; $restokKen = 0;

        if(Auth::user()->roles == 'ADMIN') {
            foreach($barang as $b) {
                $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                        ->selectRaw('id_barang, sum(stok) as total')
                        ->where('id_barang', $b->id)->where('tipe', 'BIASA')->get();
                if($stok[0]->total <= $b->subjenis->limit)
                    $restok++;
            }
            $lastTrans = SalesOrder::latest()->take(6)->get();
            foreach($lastTrans as $l) {
                $totalQty = DetilSO::selectRaw('sum(qty) as qty')->where('id_so', $l->id)->get();
                $l->{'qty'} = $totalQty[0]->qty;
                $l->tgl_so = Carbon::parse($l->tgl_so)->format('d-M-y');
            }
        } elseif(Auth::user()->roles == 'KENARI') {
            foreach($barang as $b) {
                $stokKen = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                            ->selectRaw('id_barang, sum(stok) as total')
                            ->where('id_barang', $b->id)->where('tipe', 'KENARI')->get();
                if($stokKen[0]->total <= ($b->subjenis->limit / 2))
                    $restokKen++;
            }
            $lastTransKen = SalesOrder::join('users', 'users.id', 'so.id_user')->select('so.id as id', 'so.*')
                        ->where('roles', 'KENARI')->latest('so.created_at')->take(6)->get();
            foreach($lastTransKen as $l) {
                $totalQty = DetilSO::selectRaw('sum(qty) as qty')->where('id_so', $l->id)->get();
                $l->{'qty'} = $totalQty[0]->qty;
                $l->tgl_so = Carbon::parse($l->tgl_so)->format('d-M-y');
            }
        }

        /* foreach($barang as $b) {
            $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                    ->selectRaw('id_barang, sum(stok) as total')
                    ->where('id_barang', $b->id)->where('tipe', 'BIASA')->get();
            if($stok[0]->total <= $b->subjenis->limit)
                $restok++;

            $stokKen = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                        ->selectRaw('id_barang, sum(stok) as total')
                        ->where('id_barang', $b->id)->where('tipe', 'KENARI')->get();
            if($stok[0]->total <= ($b->subjenis->limit / 2))
                $restokKen++;
        }  */

        $fakturCount = []; $fakturCountKen = [];
        $status = ['APPROVE_LIMIT', 'BATAL', 'CETAK', 'INPUT', 'UPDATE', 'LIMIT'];
        if((Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'KENARI')) {
            for($i = 0; $i < sizeof($status); $i++) {
                if(Auth::user()->roles == 'ADMIN') {
                    $fakturPerStatus = SalesOrder::where('status', $status[$i])
                                ->whereYear('tgl_so', $tahun)->count();
                    $fakturCount[$i] = $fakturPerStatus;
                } elseif (Auth::user()->roles == 'KENARI') {
                    $fakturPerStatusKen = SalesOrder::join('users', 'users.id', 'so.id_user')
                                    ->where('roles', 'KENARI')->where('status', $status[$i])
                                    ->whereYear('tgl_so', $tahun)->count();
                    $fakturCountKen[$i] = $fakturPerStatusKen;
                }
            }
        }

        if(Auth::user()->roles == 'AR') {
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
                $total = round(($detil[0]->total * 100) / (($s->total != 0) ? $s->total - $s->retur : (($detil[0]->total != 0) ? $detil[0]->total : 1 + 100)), 2);
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
        }

        if(Auth::user()->roles == 'AP') {
            $buyAnnual = BarangMasuk::where('status', '!=', 'BATAL')
                        ->whereYear('tanggal', $tahun)->count();
            $buyMonthly = BarangMasuk::where('status', '!=', 'BATAL')
                        ->whereMonth('tanggal', $bulan)->count();

            $payableCount = AccPayable::where('keterangan', 'BELUM LUNAS')->count();
            $tagihanAnnual = BarangMasuk::selectRaw('sum(total) as sales')
                            ->whereNotIn('status', ['BATAL'])->get();
            $bayar = DetilAP::selectRaw('sum(transfer) as total')->get();
            $retur = AP_Retur::selectRaw('sum(total) as total')->get();
            $payable = $tagihanAnnual[0]->sales - $bayar[0]->total - $retur[0]->total;
            // $payable = $tagihanAnnual[0]->sales;
            $payableDiskon = BarangMasuk::where('diskon', 'F')->count();

            $q1 = 0; $q2 = 0; $q3 = 0; $q4 = 0;
            $tagihan = BarangMasuk::join('ap', 'ap.id_bm', 'barangmasuk.id_faktur')
                ->select('barangmasuk.*')
                ->selectRaw('sum(total) as total')
                ->where('status', '!=', 'BATAL')
                ->where('keterangan', 'BELUM LUNAS')
                ->groupBy('id_faktur')->get();

            foreach($tagihan as $t) {
                $detil = DetilAP::selectRaw('sum(transfer) as total')
                        ->where('id_ap', $t->ap->id)->get();
                $retur = AP_Retur::selectRaw('sum(total) as total')
                        ->where('id_ap', $t->ap->id)->get();
                $t->total -= $retur[0]->total;
                $t->{'transfer'} = $detil[0]->total;
                if($t->total != 0)
                    $total = round(($detil[0]->total * 100) / $t->total, 2);
                else
                    $total = round($detil[0]->total * 100, 2);

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
        }

        if(Auth::user()->roles == 'OFFICE02') {
            $salesAnnOff = SalesOrder::selectRaw('sum(total) as sales')
                            ->where('id_sales', 'SLS03')
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereYear('tgl_so', $tahun)->get();
            $salesMonOff = SalesOrder::selectRaw('sum(total) as sales')
                            ->where('id_sales', 'SLS03')
                            ->whereMonth('tgl_so', $bulan)
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])->get();
            $transAnnOff = SalesOrder::where('id_sales', 'SLS03')
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereYear('tgl_so', $tahun)->count();
            $transMonOff = SalesOrder::where('id_sales', 'SLS03')
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereMonth('tgl_so', $bulan)->count();
            $returOff = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                        ->join('so', 'so.id', 'ar.id_so')
                        ->selectRaw('sum(ar_retur.total) as total')
                        ->where('id_sales', 'SLS03')
                        ->whereYear('tanggal', $tahun)->get();
            $returMonOff = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                        ->join('so', 'so.id', 'ar.id_so')
                        ->selectRaw('sum(ar_retur.total) as total')
                        ->where('id_sales', 'SLS03')
                        ->whereMonth('tgl_so', $bulan)->get();
            $cicilOff = DetilAR::join('ar', 'ar.id', 'detilar.id_ar')
                        ->join('so', 'so.id', 'ar.id_so')
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

            $tipeCountOff = [];
            for($i = 0; $i < sizeof($tipe); $i++) {
                $salesPerTypeOff = SalesOrder::join('customer', 'customer.id', 'so.id_customer')
                            ->where('customer.id_sales', 'SLS03')
                            ->where('kategori', $tipe[$i])
                            ->whereNotIn('status', ['BATAL', 'LIMIT'])
                            ->whereYear('tgl_so', $tahun)->count();
                $tipeCountOff[$i] = $salesPerTypeOff;
            }
        }

        if(Auth::user()->roles == 'SUPER') {
            $data = [
                'salesAnnual' => $salesAnnual,
                'salesMonthly' => $salesMonthly,
                'transAnnual' => $transAnnual,
                'transMonthly' => $transMonthly,
                'receivable' => $receivable,
                'salesPerMonth' => $salesPerMonth,
                'arrTotal' => $arrTotal,
                'salesPerType' => $tipeCount,
            ];
        } elseif(Auth::user()->roles == 'ADMIN') {
            $data = [
                'transAnnual' => $transAnnual,
                'transMonthly' => $transMonthly,
                'needPrint' => $needPrint,
                'stillPending' => $stillPending,
                'restock' => $restok,
                'fakturPerStatus' => $fakturCount,
                'lastTrans' => $lastTrans,
            ];
        } elseif(Auth::user()->roles == 'AR') {
            $data = [
                'transAnnual' => $transAnnual,
                'transMonthly' => $transMonthly,
                'receivCount' => $receivCount,
                'receivTempo' => $receivTempo,
                'receiv' => $receiv,
                'receivable' => $receivable,
                'bigReceiv' => $bigReceiv,
                'barCicil' => $barCicil,
            ];
        } elseif(Auth::user()->roles == 'AP') {
            $data = [
                'buyAnnual' => $buyAnnual,
                'buyMonthly' => $buyMonthly,
                'payableCount' => $payableCount,
                'payable' => $payable,
                'payableDiskon' => $payableDiskon,
                'barBayar' => $barBayar,
                'bigTagihan' => $bigTagihan,
            ];
        } elseif(Auth::user()->roles == 'KENARI') {
            $data = [
                'transAnnualKen' => $transAnnualKen,
                'transMonthlyKen' => $transMonthlyKen,
                'needPrintKen' => $needPrintKen,
                'stillPendingKen' => $stillPendingKen,
                'restockKen' => $restokKen,
                'fakturPerStatusKen' => $fakturCountKen,
                'lastTransKen' => $lastTransKen,
            ];
        } elseif(Auth::user()->roles == 'OFFICE02') {
            $data = [
                'salesAnnOff' => $salesAnnOff,
                'salesMonOff' => $salesMonOff,
                'transAnnOff' => $transAnnOff,
                'transMonOff' => $transMonOff,
                'receivableOff' => $receivableOff,
                'arrTotalOff' => $arrTotalOff,
                'salesPerTypeOff' => $tipeCountOff,
            ];
        } else {
            $data = [];
        }

        return view('pages.dashboard', $data);
    }
}
