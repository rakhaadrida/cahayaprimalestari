<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use App\Models\AccPayable;
use Carbon\Carbon;

class AccPayableController extends Controller
{
    public function index() {
        $ap = AccPayable::with(['bm'])->where('keterangan', 'BELUM LUNAS')
                ->orderBy('created_at', 'desc')->get();
        $data = [
            'ap' => $ap
        ];

        return view('pages.payable.index', $data);
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

        $ap = AccPayable::with(['bm'])->where('keterangan', $status)
                ->orWhereMonth('created_at', $month)
                ->orWhereBetween('created_at', [$awal.' 00:00:00', $akhir.' 00:00:00'])
                ->orderBy('created_at', 'desc')->get();
        
        $data = [
            'ap' => $ap,
            'bulan' => $request->bulan,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir,
            'status' => $request->status
        ];

        return view('pages.payable.show', $data);
    }

    public function detail(Request $request, $id) {
        $items = BarangMasuk::with(['supplier'])->where('id', $id)->get();
        $data = [
            'items' => $items,
            'id' => $id
        ];

        return view('pages.payable.detail', $data);
    }

    public function process(Request $request) {
        $detil = DetilBM::where('id_bm', $request->kode)->get();
        $bm = BarangMasuk::where('id', $request->kode)->first();

        foreach($detil as $d) {
            $d->diskon = $request->{"dis".$d->id_barang};
            $d->save();
        }

        $bm->{'total'} = str_replace(".", "", $request->subtotal);
        $bm->save();

        return redirect()->route('ap');
    }

    public function transfer(Request $request) {
        if($request->kodeBM != "") {
            $arrKode = explode(",", $request->kodeBM);
            for($i = 0; $i < sizeof($arrKode); $i++) {
                $ap = AccPayable::where('id_bm', $arrKode[$i])->first();
                $bm = BarangMasuk::where('id', $arrKode[$i])->get();
                if($bm[0]->total == str_replace(",", "", $request->{"tr".$arrKode[$i]})) 
                        $status = 'LUNAS';
                    else 
                        $status = 'BELUM LUNAS';

                $ap->{'tgl_bayar'} = Carbon::now()->toDateString();
                $ap->{'transfer'} = str_replace(",", "", $request->{"tr".$arrKode[$i]});
                $ap->{'keterangan'} = $status;
                $ap->save();
            }
        }

        return redirect()->route('ap');
    }
}
