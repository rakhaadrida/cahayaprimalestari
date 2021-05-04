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
use App\Models\ReturBeli;
use App\Models\DetilRB;
use App\Models\ReturTerima;
use App\Models\DetilRT;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccPayableController extends Controller
{
    public function index() {
        $apLast = AccPayable::join('barangmasuk', 'barangmasuk.id_faktur', 'ap.id_bm')
                ->join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                ->select('ap.id as id', 'ap.*', 'barangmasuk.tanggal', 'barangmasuk.tempo', 'supplier.nama as namaSupp')
                ->orderBy('ap.updated_at', 'desc')->take(1)->get();

        if($apLast->count() != 0)
            $ap = AccPayable::join('barangmasuk', 'barangmasuk.id_faktur', 'ap.id_bm')
                ->join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                ->select('ap.id as id', 'ap.*', 'barangmasuk.tanggal', 'barangmasuk.tempo', 'supplier.nama as namaSupp')
                ->where('ap.id', '!=', $apLast->first()->id)
                ->orderBy('ap.created_at', 'desc')->get();
        else
            $ap = AccPayable::join('barangmasuk', 'barangmasuk.id_faktur', 'ap.id_bm')
                ->join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                ->select('ap.id as id', 'ap.*', 'barangmasuk.tanggal', 'barangmasuk.tempo', 'supplier.nama as namaSupp')->orderBy('ap.created_at', 'desc')->get();               

        $barang = Barang::All();
        $harga = HargaBarang::All();

        $data = [
            'ap' => $ap,
            'apLast' => $apLast,
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
            $ap = AccPayable::join('barangmasuk', 'barangmasuk.id_faktur', 'ap.id_bm')
                ->join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                ->select('ap.id as id', 'ap.*', 'barangmasuk.tanggal', 'barangmasuk.tempo', 'supplier.nama as namaSupp')->whereIn('keterangan', [$status[0], $status[1]])
                ->orderBy('ap.created_at', 'desc')->get();
        } else {
            $ap = AccPayable::join('barangmasuk', 'barangmasuk.id_faktur', 'ap.id_bm')
                ->join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                ->select('ap.id as id', 'ap.*', 'barangmasuk.tanggal', 'barangmasuk.tempo', 'supplier.nama as namaSupp')
                ->whereIn('keterangan', [$status[0], $status[1]])
                ->where(function ($q) use ($awal, $akhir, $month) {
                    $q->whereMonth('barangmasuk.tanggal', $month)
                    ->orWhereBetween('barangmasuk.tanggal', [$awal, $akhir]);
                })->groupBy('ap.id')->orderBy('barangmasuk.created_at', 'desc')->get();
        }

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
        $items = BarangMasuk::with(['supplier'])->where('id_faktur', $id)->where('status', '!=', 'BATAL')->get();
        $potongan = BarangMasuk::select(DB::raw('sum(potongan) as potongan'))->where('id_faktur', $id)->get();

        $data = [
            'items' => $items,
            'potongan' => $potongan,
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
                $d->harga = str_replace(".", "", $request->{"harga".$i->id.$d->id_barang});
                $d->diskon = $request->{"dis".$i->id.$d->id_barang};
                $d->disPersen = $request->{"diskon".$i->id.$d->id_barang};
                $d->save();

                $total += (int) (str_replace(".", "", $request->{"hpp".$i->id.$d->id_barang}));
            }

            $i->tempo = $request->tempo != '' ? $request->tempo : 0;
            // $i->total = str_replace(".", "", $request->grandtotal);
            $i->total = $total;
            // $i->potongan = str_replace(".", "", $request->potongan);
            $i->diskon = 'T';
            $i->save();
        }

        $items[0]->potongan = str_replace(".", "", $request->potongan);
        $items[0]->save();

        return redirect()->route('ap');
    }

    public function createTransfer($id) {
        $item = AccPayable::where('id_bm', $id)->get();
        $retur = AP_Retur::selectRaw('sum(total) as total')->where('id_ap', $item->first()->id)->get();
        $detilap = DetilAP::where('id_ap', $item->first()->id)->orderBy('tgl_bayar')->get();
        $totalBM = BarangMasuk::select(DB::raw('sum(total) as totBM'))->where('id_faktur', $id)
                    ->where('status', '!=', 'BATAL')->get();
        $potBM = BarangMasuk::select(DB::raw('sum(potongan) as potongan'))->where('id_faktur', $id)->get();

        $data = [
            'item' => $item,
            'retur' => $retur,
            'detilap' => $detilap,
            'totalBM' => $totalBM,
            'potBM' => $potBM
        ];

        return view('pages.payable.detailNew', $data);
    }

    public function transfer(Request $request) {
        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $tglBayar = $request->tgl;
        $tglBayar = $this->formatTanggal($tglBayar, 'Y-m-d');

        if($request->kurangAkhir == 0)
            $status = 'LUNAS';
        else 
            $status = 'BELUM LUNAS';

        $items = DetilAP::where('id_ap', $request->kodeAP)->get();
        $j = 0;
        foreach($items as $i) {
            if($j < $request->jumBaris) {
                $tglDetil = $request->tgldetil[$j];
                $tglDetil = $this->formatTanggal($tglDetil, 'Y-m-d');

                if(($tglDetil != $i->tgl_bayar) || ($request->bayardetil[$j] != $i->transfer)) {
                    $i->tgl_bayar = $tglDetil;
                    $i->transfer = str_replace(".", "", $request->bayardetil[$j]);
                    $i->save();
                }
            } else {
                $i->delete();
            }

            $j++;
        }

        $lastcode = DetilAP::selectRaw('max(id_bayar) as id')->whereYear('created_at', $waktu->year)
                    ->whereMonth('created_at', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'TRS'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        if(($request->bayar != '') && ($request->tgl != '')) {
            DetilAP::create([
                'id_ap' => $request->kodeAP,
                'id_bayar' => $newcode,
                'tgl_bayar' => $tglBayar,
                'transfer' => (int) str_replace(".", "", $request->bayar)
            ]);
        }

        $ap = AccPayable::where('id', $request->kodeAP)->first();
        $ap->{'keterangan'} = $status;
        $ap->save();

        return redirect()->route('ap');
    }

    public function createRetur($id) {
        $item = AccPayable::where('id_bm', $id)->get();
        $retur = DetilRAP::join('ap_retur', 'ap_retur.id', 'detilrap.id_retur')
                ->where('id_ap', $item->first()->id)->orderBy('tgl_retur', 'asc')->get();
        $total = AP_Retur::select('id')->selectRaw('sum(total) as total')
                ->where('id_ap', $item->first()->id)->get();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $returBeli = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                    ->join('returbeli', 'returbeli.id', 'returterima.id_retur')
                    ->where('id_supplier', $item->first()->bm->first()->id_supplier)
                    ->where('potong', '!=', 0)->groupBy('id_retur')->get();
        $rt = ReturTerima::where('id', $total->first()->id)->get();

        $data = [
            'item' => $item,
            'retur' => $retur,
            'total' => $total,
            'barang' => $barang,
            'harga' => $harga,
            'returBeli' => $returBeli,
            'rt' => $rt
        ];

        return view('pages.payable.returNew', $data);
    }

    public function showRetur(Request $request, $id) {
        $item = AccPayable::where('id_bm', $id)->get();
        $retur = DetilRAP::join('ap_retur', 'ap_retur.id', 'detilrap.id_retur')
                ->where('id_ap', $item->first()->id)->orderBy('tgl_retur', 'asc')->get();
        $total = AP_Retur::selectRaw('sum(total) as total')->where('id_ap', $item->first()->id)->get();
        // $barang = Barang::All();
        // $harga = HargaBarang::All();

        $returBeli = ReturBeli::where('id_supplier', $item->first()->bm->first()->id_supplier)->get();
        $detilRB = DetilRB::where('id_retur', $request->nomorRetur)->get();
        $kode = $request->nomorRetur;

        $data = [
            'item' => $item,
            'retur' => $retur,
            'total' => $total,
            // 'barang' => $barang,
            // 'harga' => $harga,
            'returBeli' => $returBeli,
            'detilRB' => $detilRB,
            'kode' => $kode
        ];

        return view('pages.payable.returNewShow', $data);
    }

    public function retur(Request $request) {
        $jumBaris = $request->jumBaris;
        $gudang = Gudang::where('tipe', 'RETUR')->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = AP_Retur::selectRaw('max(id) as id')->whereYear('tanggal', $waktu->year)
                    ->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'RTP'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        // $tanggal = Carbon::now('+07:00')->toDateString();
        // $total = (str_replace(".", "", $request->{"harga".$request->kode}) * 
        //         $request->{"qty".$request->kode}) - str_replace(".", "", $request->{"diskonRp".$request->kode});

        AP_Retur::create([
            'id' => $newcode,
            'id_ap' => $request->kode,
            'tanggal' => Carbon::now('+07:00')->toDateString(),
            'total' => 0,
            'id_user' => Auth::user()->id
        ]);

        ReturTerima::create([
            'id' => $newcode,
            'id_retur' => $request->nomorRetur,
            'tanggal' => Carbon::now('+07:00')->toDateString(),
        ]);

        $total = 0; $jum = 0;
        for($i = 0; $i < $jumBaris; $i++) {
            $tglRetur = $request->tglDetil[$i];
            $tglRetur = $this->formatTanggal($tglRetur, 'Y-m-d');

            if($request->diskonDetil[$i] != '') {
                DetilRAP::create([
                    'id_retur' => $newcode,
                    'id_barang' => $request->kodeDetil[$i],
                    'tgl_retur' => $tglRetur,
                    'qty' => $request->qtyDetil[$i],
                    'harga' => str_replace(".", "", $request->hargaDetil[$i]),
                    'diskon' => $request->diskonDetil[$i],
                    'diskonRp' => str_replace(".", "", $request->diskonRpDetil[$i]),
                ]);

                DetilRT::create([
                    'id_terima' => $newcode,
                    'id_barang' => $request->kodeDetil[$i],
                    'qty_terima' => 0,
                    'qty_batal' => 0,
                    'potong' => $request->qtyDetil[$i]
                ]);

                $total += str_replace(".", "", $request->nettoDetil[$i]);
                $jum++;
            }
        }

        $ret = AP_Retur::where('id', $newcode)->first();
        $ret->{'total'} = $total;
        $ret->save();

        if($jum == $jumBaris) {
            $rb = ReturBeli::where('id', $request->nomorRetur)->first();
            $rb->{'status'} = 'LENGKAP';
            $rb->save();
        }

        return redirect()->route('ap');
    }

    public function updateRetur(Request $request, $id) {
        $jumBaris = $request->jumBaris;
        $gudang = Gudang::where('tipe', 'RETUR')->get();

        $detilRAP = DetilRAP::where('id_retur', $id)->get();

        $total = 0; $i = 0;
        foreach($detilRAP as $d) {
            $tglRetur = $request->tglDetil[$i];
            $tglRetur = $this->formatTanggal($tglRetur, 'Y-m-d');

            $d->diskon = $request->diskonDetil[$i];
            $d->diskonRp = str_replace(".", "", $request->diskonRpDetil[$i]);
            $d->save();

            $total += str_replace(".", "", $request->nettoDetil[$i]);
            $i++;
        }

        $ret = AP_Retur::where('id', $id)->first();
        $ret->{'total'} = $total;
        $ret->save();

        return redirect()->route('ap');
    }

    public function batalRetur(Request $request, $id) {
        $apRetur = AP_Retur::where('id', $id)->delete();
        $detilap = DetilRAP::where('id_retur', $id)->delete();
        $rt = ReturTerima::where('id', $id)->delete();
        $detilrt = DetilRT::where('id_terima', $id)->delete();

        $rb = ReturBeli::where('id', $request->nomorRetur)->first();
        $rb->{'status'} = 'INPUT';
        $rb->save();

        return redirect()->route('ap');
    }
}
