<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\AccReceivable;
use Carbon\Carbon;

class AccReceivableController extends Controller
{
    public function index() {
        $tahun = Carbon::now();
        $tahun = $tahun->month;
        $ar = AccReceivable::with(['so'])->get();
        $data = [
            'ar' => $ar
        ];

        return view('pages.receivable.index', $data);
    }

    public function show(Request $request) {
        if($request->status == 'ALL')  {
            $status[0] = 'LUNAS';
            $status[1] = 'BELUM LUNAS';
        }
        else {
            $status[0] = $request->status;
            $status[1] = '';
        }

        $awal = $request->tglAwal;
        $akhir = $request->tglAkhir;

        if(($request->bulan == '') || ($request->tglAwal == ''))
            $isi = 1;

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

        if($isi != 1) {
            $ar = AccReceivable::with(['so'])->whereMonth('updated_at', $month)
                ->orWhereBetween('updated_at', [$awal, $akhir])
                ->orWhereIn('keterangan', [$status[0], $status[1]])
                ->get();
        } else {
            $ar = AccReceivable::with(['so'])->join('so', 'so.id', '=', 'ar.id_so')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('so.tgl_so', $month)
                    ->orWhereBetween('so.tgl_so', [$awal, $akhir]);
                })->get();
        }
        
        
        $data = [
            'ar' => $ar,
            'bulan' => $request->bulan,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir,
            'status' => $request->status
        ];

        return view('pages.receivable.show', $data);
    }

    public function process(Request $request) {
        if($request->kodeSO != "") {
            $arrKode = explode(",", $request->kodeSO);
            for($i = 0; $i < sizeof($arrKode); $i++) {
                $ar = AccReceivable::where('id_so', $arrKode[$i])->first();
                $so = SalesOrder::where('id', $arrKode[$i])->get();
                if($so[0]->total == str_replace(",", "", $request->{"cic".$arrKode[$i]})) 
                        $status = 'LUNAS';
                    else 
                        $status = 'BELUM LUNAS';

                if($ar == null) {
                    AccReceivable::create([
                        'id_so' => $arrKode[$i],
                        'tgl_bayar' => Carbon::now()->toDateString(),
                        'cicil' => (int) str_replace(",", "", $request->{"cic".$arrKode[$i]}),
                        'retur' => (int) str_replace(",", "", $request->{"ret".$arrKode[$i]}),
                        'keterangan' => $status
                    ]);
                }
                else {
                    $ar->{'tgl_bayar'} = Carbon::now()->toDateString();
                    $ar->{'cicil'} = (int) str_replace(",", "", $request->{"cic".$arrKode[$i]});
                    $ar->{'retur'} = (int) str_replace(",", "", $request->{"ret".$arrKode[$i]});
                    $ar->{'keterangan'} = $status;
                    $ar->save();
                }
            }
        }

        return redirect()->route('ar');
    }
}
