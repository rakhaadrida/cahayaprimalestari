<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use App\Models\AccPayable;
use App\Models\DetilAP;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccPayableController extends Controller
{
    public function index() {
        $ap = AccPayable::with(['bm'])->orderBy('created_at', 'desc')->get();
        $data = [
            'ap' => $ap
        ];

        return view('pages.payable.index', $data);
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
            $ap = AccPayable::with(['bm'])->whereIn('keterangan', [$status[0], $status[1]])
                ->get();
        } else {
            $ap = AccPayable::with(['bm'])->join('barangmasuk', 'barangmasuk.id', '=', 
                'ap.id_bm')
                ->select('*', 'barangmasuk.id as id_bm', 'ap.id as id')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('barangmasuk.tanggal', $month)
                    ->orWhereBetween('barangmasuk.tanggal', [$awal, $akhir]);
                })->get();
        }
        
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

        return view('pages.payable.diskon', $data);
    }

    public function process(Request $request) {
        $detil = DetilBM::where('id_bm', $request->kode)->get();
        $bm = BarangMasuk::where('id', $request->kode)->first();

        foreach($detil as $d) {
            $d->diskon = $request->{"dis".$d->id_barang};
            $d->disPersen = (int) (str_replace(".", "", $request->{"disRp".$d->id_barang}));
            $d->hpp = (int) (str_replace(".", "", $request->{"hpp".$d->id_barang}) / $d->qty);
            $d->save();
        }

        $bm->{'total'} = str_replace(".", "", $request->subtotal);
        $bm->{'status'} = "LENGKAP";
        $bm->save();

        return redirect()->route('ap');
    }

    public function transfer(Request $request) {
        $lastcode = DetilAP::max('id_bayar');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TRS'.sprintf("%04s", $lastnumber);

        if($request->kodeBM != "") {
            $arrKode = explode(",", $request->kodeBM);
            $arrKode = array_unique($arrKode);
            sort($arrKode);
            for($i = 0; $i < sizeof($arrKode); $i++) {
                $ap = AccPayable::where('id_bm', $arrKode[$i])->first();
                $total = DetilAP::join('ap', 'ap.id', '=', 'detilap.id_ap')
                            ->select('ap.id', DB::raw('sum(transfer) as totTransfer'))
                            ->where('ap.id_bm', $arrKode[$i])
                            ->groupBy('ap.id')->get();
                $bm = BarangMasuk::where('id', $arrKode[$i])->get();

                if($total->count() == 0) 
                    $totTransfer = 0;
                else 
                    $totTransfer = $total[0]->totTransfer;

                if($bm[0]->total == str_replace(",", "", $request->{"tr".$arrKode[$i]})) 
                        $status = 'LUNAS';
                    else 
                        $status = 'BELUM LUNAS';

                $ap->{'keterangan'} = $status;
                $ap->save();

                DetilAP::create([
                    'id_ap' => $ap->{'id'},
                    'id_bayar' => $newcode,
                    'tgl_bayar' => Carbon::now()->toDateString(),
                    'transfer' => (int) str_replace(",", "", $request->{"tr".$arrKode[$i]}) - $totTransfer
                ]);
            }
        }

        return redirect()->route('ap');
    }
}
