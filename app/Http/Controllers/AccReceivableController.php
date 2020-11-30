<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccReceivableController extends Controller
{
    public function index() {
        $ar = AccReceivable::with(['so'])->get();
        $arOffice = AccReceivable::with(['so'])
                ->select('ar.id', 'ar.id_so', 'ar.keterangan', 'ar.retur')
                ->join('so', 'so.id', 'ar.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->where('id_sales', 'SLS03')->get();
                // return response()->json($arOffice);
        $data = [
            'ar' => $ar,
            'arOffice' => $arOffice
        ];

        return view('pages.receivable.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
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
        $awal = $this->formatTanggal($awal, 'Y-m-d');
        $akhir = $request->tglAkhir;
        $akhir = $this->formatTanggal($akhir, 'Y-m-d');

        $isi = 2;
        if(($request->bulan == '') && ($request->tglAwal == ''))
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

        if($isi == 1) {
            $ar = AccReceivable::with(['so'])->whereIn('keterangan', [$status[0], $status[1]])
                ->get();

            // $ar = AccReceivable::with(['so'])->whereMonth('updated_at', $month)
            //     ->orWhereBetween('updated_at', [$awal, $akhir])
            //     ->orWhereIn('keterangan', [$status[0], $status[1]])
            //     ->get();
        } else {
            // $ar = AccReceivable::with(['so'])->whereMonth('updated_at', $month)
            //     ->orWhereBetween('updated_at', [$awal, $akhir])
            //     ->orWhereIn('keterangan', [$status[0], $status[1]])
            //     ->get();

            $ar = AccReceivable::with(['so'])->join('so', 'so.id', '=', 'ar.id_so')
                ->select('*', 'so.id as id_so', 'ar.id as id')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('so.tgl_so', $month)
                    ->orWhereBetween('so.tgl_so', [$awal, $akhir]);
                })->get();
        }

        // var_dump($isi.' = '.$ar[0]->id.' = '.$ar[0]->id_so);
        
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
        $lastcode = DetilAR::max('id_cicil');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'CIC'.sprintf("%04s", $lastnumber);

        $tglBayar = $request->{"tgl".$request->kode};
        $tglBayar = $this->formatTanggal($tglBayar, 'Y-m-d');

        $ar = AccReceivable::where('id_so', $request->kode)->first();
        $total = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                    ->select('ar.id', DB::raw('sum(cicil) as totCicil'))
                    ->where('ar.id_so', $request->kode)
                    ->groupBy('ar.id')->get();
        $so = SalesOrder::where('id', $request->kode)->get();

        if($total->count() == 0) 
            $totCicil = 0;
        else 
            $totCicil = $total[0]->totCicil;

        if($so[0]->total == str_replace(",", "", $request->{"cicil".$request->kode}) + $totCicil)
                $status = 'LUNAS';
            else 
                $status = 'BELUM LUNAS';

        // $ar->{'retur'} = (int) str_replace(",", "", $request->{"ret".$arrKode[$i]});
        $ar->{'keterangan'} = $status;
        $ar->save();

        DetilAR::create([
            'id_ar' => $ar->{'id'},
            'id_cicil' => $newcode,
            'tgl_bayar' => $tglBayar,
            'cicil' => (int) str_replace(",", "", $request->{"cicil".$request->kode})
        ]);

        /*
        if($request->kodeSO != "") {
            $arrKode = explode(",", $request->kodeSO);
            $arrKode = array_unique($arrKode);
            sort($arrKode);
            for($i = 0; $i < sizeof($arrKode); $i++) {
                $ar = AccReceivable::where('id_so', $arrKode[$i])->first();
                $total = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                            ->select('ar.id', DB::raw('sum(cicil) as totCicil'))
                            ->where('ar.id_so', $arrKode[$i])
                            ->groupBy('ar.id')->get();
                $so = SalesOrder::where('id', $arrKode[$i])->get();

                if($total->count() == 0) 
                    $totCicil = 0;
                else 
                    $totCicil = $total[$i]->totCicil;

                if($so[0]->total == str_replace(",", "", $request->{"cic".$arrKode[$i]}))
                        $status = 'LUNAS';
                    else 
                        $status = 'BELUM LUNAS';

                $ar->{'retur'} = (int) str_replace(",", "", $request->{"ret".$arrKode[$i]});
                $ar->{'keterangan'} = $status;
                $ar->save();

                DetilAR::create([
                    'id_ar' => $ar->{'id'},
                    'id_cicil' => $newcode,
                    'tgl_bayar' => Carbon::now()->toDateString(),
                    'cicil' => (int) str_replace(",", "", $request->{"cic".$arrKode[$i]}) - $totCicil
                ]);
            }
        }*/

        return redirect()->route('ar');
    }
}
