<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use App\Models\AccPayable;
use App\Models\DetilAP;
use App\Models\Barang;
use App\Models\HargaBarang;
use App\Models\Gudang;
use App\Models\AP_Retur;
use App\Models\DetilRAP;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AccPayableController extends Controller
{
    public function index() {
        $ap = AccPayable::with(['bm'])->orderBy('created_at', 'desc')->get();
        // return response()->json($ap);
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $data = [
            'ap' => $ap,
            'barang' => $barang,
            'harga' => $harga
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
            $ap = AccPayable::join('barangmasuk', 'barangmasuk.id_faktur', 
                'ap.id_bm')->select('ap.*')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('barangmasuk.tanggal', $month)
                    ->orWhereBetween('barangmasuk.tanggal', [$awal, $akhir]);
                })->groupBy('ap.id')->get();
        }

        // return response()->json($ap);

        $barang = Barang::All();
        $harga = HargaBarang::All();
        
        $data = [
            'ap' => $ap,
            'barang' => $barang,
            'harga' => $harga,
            'bulan' => $request->bulan,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir,
            'status' => $request->status
        ];

        return view('pages.payable.show', $data);
    }

    public function detail(Request $request, $id) {
        $items = BarangMasuk::with(['supplier'])->where('id_faktur', $id)->get();
        $data = [
            'items' => $items,
            'id' => $id
        ];

        return view('pages.payable.diskon', $data);
    }

    public function process(Request $request) {
        $items = BarangMasuk::where('id_faktur', $request->kode)->get();

        foreach($items as $i) {
            $total = 0;
            $detil = DetilBM::where('id_bm', $i->id)->get();
            // $bm = BarangMasuk::where('id', $request->kode)->first();

            foreach($detil as $d) {
                $d->diskon = $request->{"dis".$i->id.$d->id_barang};
                $d->disPersen = (float) (str_replace(",", "", $request->{"diskon".$i->id.$d->id_barang}));
                $d->hpp = (int) (str_replace(".", "", $request->{"hpp".$i->id.$d->id_barang}) / $d->qty);
                $d->save();

                $total += (int) (str_replace(".", "", $request->{"hpp".$i->id.$d->id_barang}));
            }

            // $i->total = str_replace(".", "", $request->subtotal);
            $i->tempo = $request->tempo != '' ? $request->tempo : 0;
            $i->total = $total;
            $i->diskon = 'T';
            $i->save();
        }

        return redirect()->route('ap');
    }

    public function transfer(Request $request) {
        $lastcode = DetilAP::max('id_bayar');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TRS'.sprintf("%04s", $lastnumber);

        $tglBayar = $request->{"tgl".$request->kode};
        $tglBayar = $this->formatTanggal($tglBayar, 'Y-m-d');

        $ap = AccPayable::where('id_bm', $request->kode)->first();
        $total = DetilAP::join('ap', 'ap.id', '=', 'detilap.id_ap')
                    ->select('ap.id', DB::raw('sum(transfer) as totTransfer'))
                    ->where('ap.id_bm', $request->kode)
                    ->groupBy('ap.id')->get();
        $bm = BarangMasuk::selectRaw('sum(total) as totBM')
                ->where('id_faktur', $request->kode)
                ->groupBy('id_faktur')->get();

        if($total->count() == 0) 
            $totTransfer = 0;
        else 
            $totTransfer = $total[0]->totTransfer;

        if($bm[0]->totBM == str_replace(",", "", $request->{"bayar".$request->kode}) + $totTransfer) 
            $status = 'LUNAS';
        else 
            $status = 'BELUM LUNAS';

        $ap->{'keterangan'} = $status;
        $ap->save();

        DetilAP::create([
            'id_ap' => $ap->{'id'},
            'id_bayar' => $newcode,
            'tgl_bayar' => $tglBayar,
            'transfer' => (int) str_replace(",", "", $request->{"bayar".$request->kode})
        ]);

        /* if($request->kodeBM != "") {
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
        } */

        return redirect()->route('ap');
    }

    public function retur(Request $request) {
        $gudang = Gudang::where('retur', 'T')->get();

        $lastcode = AP_Retur::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'RTP'.sprintf("%04s", $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $total = (str_replace(".", "", $request->{"harga".$request->kode}) * 
                $request->{"qty".$request->kode}) - str_replace(".", "", $request->{"diskonRp".$request->kode});

        AP_Retur::create([
            'id' => $newcode,
            'id_ap' => $request->kode,
            'tanggal' => $tanggal,
            'total' => $total,
            'id_user' => Auth::user()->id
        ]);

        $tglRetur = $request->{"tglRetur".$request->kode};
        $tglRetur = $this->formatTanggal($tglRetur, 'Y-m-d');

        DetilRAP::create([
            'id_retur' => $newcode,
            'id_barang' => $request->{"kodeBarang".$request->kode},
            'tgl_retur' => $tglRetur,
            'qty' => $request->{"qty".$request->kode},
            'harga' => str_replace(".", "", $request->{"harga".$request->kode}),
            'diskon' => $request->{"diskon".$request->kode},
            'diskonRp' => str_replace(".", "", $request->{"diskonRp".$request->kode}),
        ]);

        $stok = StokBarang::where('id_barang', $request->{"kodeBarang".$request->kode})
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'F')->first();
            
        if($stok == NULL) {
            StokBarang::create([
                'id_barang' => $request->{"kodeBarang".$request->kode},
                'id_gudang' => $gudang[0]->id,
                'status' => 'F',
                'stok' => $request->{"qty".$request->kode}
            ]);
        } else {
            $stok->{'stok'} -= $request->{"qty".$request->kode};
            $stok->save();
        }

        return redirect()->route('ap');
    }
}
