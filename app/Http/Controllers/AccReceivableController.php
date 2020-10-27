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
        $awal = $request->tglAwal;
        $akhir = $request->tglAkhir;
        $status = $request->status;
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

        $so = SalesOrder::with(['customer'])->whereIn('status', ['CETAK', 'UPDATE'])
                ->where(function ($query) use($month, $awal, $akhir) {
                    $query->whereMonth('tgl_so', $month)->orWhereBetween('tgl_so', [$awal, $akhir]);
                })
                // ->orWhereHas('ar', function($q) use($status) {
                //     $q->where('keterangan', $status);
                // })
                ->get();
        
        $data = [
            'so' => $so,
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
                        'cicil' => str_replace(",", "", $request->{"cic".$arrKode[$i]}),
                        'retur' => (int) str_replace(",", "", $request->{"ret".$arrKode[$i]}),
                        'keterangan' => $status
                    ]);
                }
                else {
                    $ar->{'tgl_bayar'} = Carbon::now()->toDateString();
                    $ar->{'cicil'} = str_replace(",", "", $request->{"cic".$arrKode[$i]});
                    $ar->{'retur'} = (int) str_replace(",", "", $request->{"ret".$arrKode[$i]});
                    $ar->{'keterangan'} = $status;
                    $ar->save();
                }
            }
        }

        return redirect()->route('ar');
    }
}
