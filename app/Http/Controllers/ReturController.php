<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\StokBarang;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\ReturJual;
use App\Models\DetilRJ;
use App\Models\ReturBeli;
use App\Models\DetilRB;
use App\Models\ReturTerima;
use App\Models\DetilRT;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReturController extends Controller
{
    public function index() {
        $gudang = Gudang::where('retur', 'T')->get();
        $items = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                ->where('retur', 'T')->groupBy('id_barang')->get();

        $data = [
            'gudang' => $gudang,
            'items' => $items
        ];

        return view('pages.retur.stok', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function createPenjualan() {
        $customer = Customer::All();
        $barang = Barang::All();

        $lastcode = ReturJual::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'RTJ'.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'customer' => $customer,
            'barang' => $barang,
            'tanggal' => $tanggal,
            'newcode' => $newcode,
        ];

        return view('pages.retur.createJual', $data);
    }

    /* public function showCreateJual(Request $request) {
        $items = DetilSO::select('id_so', 'id_barang', DB::raw('sum(qty) as qty'))
                ->where('id_so', $request->kode)->groupBy('id_barang')->get();
        $so = SalesOrder::All();

        $data = [
            'items' => $items,
            'tanggal' => $request->tanggal,
            'so' => $so
        ];

        return view('pages.retur.detailJual', $data);
    } */

    public function storeJual(Request $request, $id) {
        $gudang = Gudang::where('retur', 'T')->get();
        $tanggal = $this->formatTanggal($request->tanggal, 'Y-m-d');

        ReturJual::create([
            'id' => $id,
            'tanggal' => $tanggal,
            'id_customer' => $request->kodeCustomer,
            'status' => 'INPUT'
        ]);

        for($i = 0; $i < $request->jumBaris; $i++) {
            if($request->kodeBarang[$i] != '') {
                DetilRJ::create([
                    'id_retur' => $id,
                    'id_barang' => $request->kodeBarang[$i],
                    'tgl_kirim' => NULL,
                    'qty_retur' => $request->qty[$i],
                    'qty_kirim' => NULL,
                ]);

                $stok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'F')->first();
                
                if($stok == NULL) {
                    StokBarang::create([
                        'id_barang' => $request->kodeBarang[$i],
                        'id_gudang' => $gudang[0]->id,
                        'status' => 'F',
                        'stok' => $request->qty[$i]
                    ]);
                } else {
                    $stok->{'stok'} += $request->qty[$i];
                    $stok->save();
                }
            }
        }

        $data = [
            'status' => 'false',
            'id' => '0'
        ];

        return redirect()->route('retur-jual', $data);
    }

    public function dataReturJual($status, $id) {
        $retur = ReturJual::All();
        $gudang = Gudang::where('retur', 'T')->get();

        $data = [
            'retur' => $retur,
            'status' => $status,
            'id' => $id,
            'gudang' => $gudang,
        ];

        return view('pages.retur.indexJual', $data);
    }

    public function showReturJual(Request $request) {
        if($request->status == 'ALL')  {
            $status[0] = 'INPUT';
            $status[1] = 'LENGKAP';
            $status[2] = 'CETAK';
        }
        else {
            $status[0] = $request->status;
            $status[1] = '';
            $status[2] = '';
        }

        $awal = $request->tglAwal;
        if($awal == NULL)
            $awal = '0000-00-00';
        else
            $awal = $this->formatTanggal($awal, 'Y-m-d');


        $akhir = $request->tglAkhir;
        if($akhir == NULL)
            $akhir = '0000-00-00';
        else
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
            $retur = ReturJual::whereIn('status', [$status[0], $status[1], $status[2]])
                    ->where('tipe', 'Jual')->get();
        } else {
            $retur = ReturJual::whereIn('retur.status', [$status[0], $status[1], $status[2]])
                    ->where('tipe', 'Jual')
                    ->where(function ($q) use ($awal, $akhir, $month) {
                        $q->whereMonth('tanggal', $month)
                        ->orWhereBetween('tanggal', [$awal, $akhir]);
                    })->get();
        }

        $data = [
            'retur' => $retur,
            'bulan' => $request->bulan,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir,
            'status' => $request->status
        ];

        return view('pages.retur.showJual', $data);
    }

    public function storeKirimJual(Request $request) {
        $lastcode = DetilRJ::max('id_kirim');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'KRM'.sprintf("%04s", $lastnumber);

        $gudang = Gudang::where('retur', 'T')->get();
        $retur = ReturJual::where('id', $request->kode)->first();
        $items = DetilRJ::where('id_retur', $request->kode)->get();

        $kirim = 0;
        foreach($items as $i) {
            if(($request->{"kirim".$request->kode.$i->id_barang} != '') && ($i->tgl_kirim == '')) {
                $tglKirim = $request->{"tgl".$request->kode.$i->id_barang};
                $tglKirim = $this->formatTanggal($tglKirim, 'Y-m-d');

                $i->id_kirim = $newcode;
                $i->tgl_kirim = $tglKirim;
                $i->qty_kirim = $request->{"kirim".$request->kode.$i->id_barang};
                $i->save();

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'T')->first();
                $stokBagus->{'stok'} -= (int) $request->{"kirim".$request->kode.$i->id_barang}; 
                $stokBagus->save();

                $kirim++;
            }
        }

        if($items->count() == $kirim) {
            $status = 'LENGKAP';
        }
        else 
            $status = 'INPUT';

        /* foreach($items as $i) {
            $tglKirim = $request->{"tanggal".$request->kode};
            $tglKirim = $this->formatTanggal($tglKirim, 'Y-m-d');

            $i->id_kirim = $newcode;
            $i->tgl_kirim = $tglKirim;
            $i->qty_kirim = $request->{"kirim".$request->kode.$i->id_barang};
            $i->save();

            $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                    ->where('id_gudang', $gudang[0]->id)
                    ->where('status', 'T')->first();
            $stokBagus->{'stok'} -= (int) $request->{"kirim".$request->kode.$i->id_barang}; 
            $stokBagus->save();
        } */

        // $retur->{'status'} = 'LENGKAP';
        $retur->{'status'} = $status;
        $retur->save();

        $data = [
            'status' => 'true',
            'id' => $newcode
        ];

        return redirect()->route('retur-jual', $data);
    }
    
    public function cetakKirimJual(Request $request, $id) {
        $items = DetilRJ::where('id_kirim', $id)->get();
        $data = [
            'items' => $items
        ];

        $paper = array(0,0,686,394);
        $pdf = PDF::loadview('pages.retur.cetakJual', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-so.pdf');
    }

    /* public function ttrKirimJual($id) {
        $items = ReturJual::where('id', $id)->get();

        $lastcode = TandaTerima::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TTR'.sprintf('%04s', $lastnumber);

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'newcode' => $newcode,
            'today' => $today,
            'waktu' => $waktu
        ];

        $paper = array(0,0,612,394);
        $pdf = PDF::loadview('pages.penjualan.tandaterima.cetak', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-ttr.pdf');
    } */

    public function createPembelian() {
        $supplier = Supplier::All();
        $gudang = Gudang::where('retur', 'T')->get();
        $barang = StokBarang::where('id_gudang', $gudang[0]->id)
                ->where('status', 'F')->get();

        $lastcode = ReturBeli::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'RTB'.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'supplier' => $supplier,
            'barang' => $barang,
            'newcode' => $newcode,
            'tanggal' => $tanggal
        ];

        return view('pages.retur.createBeli', $data);
    }

    public function showCreateBeli(Request $request) {
        $items = DetilBM::select('id_bm', 'id_barang', DB::raw('sum(qty) as qty'))
                ->where('id_bm', $request->kode)->groupBy('id_barang')->get();
        $bm = BarangMasuk::All();

        $data = [
            'items' => $items,
            'tanggal' => $request->tanggal,
            'bm' => $bm
        ];

        return view('pages.retur.detailBeli', $data);
    }

    public function storeBeli(Request $request, $id) {
        $gudang = Gudang::where('retur', 'T')->get();
        $tanggal = $this->formatTanggal($request->tanggal, 'Y-m-d');

        ReturBeli::create([
            'id' => $id,
            'tanggal' => $tanggal,
            'id_supplier' => $request->kodeSupplier,
            'status' => 'INPUT'
        ]);

        for($i = 0; $i < $request->jumBaris; $i++) {
            if($request->kodeBarang[$i] != '') {
                DetilRB::create([
                    'id_retur' => $id,
                    'id_barang' => $request->kodeBarang[$i],
                    'qty_retur' => $request->qty[$i]
                ]);

                $stok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'F')->first();
                
                if($stok == NULL) {
                    StokBarang::create([
                        'id_barang' => $request->kodeBarang[$i],
                        'id_gudang' => $gudang[0]->id,
                        'status' => 'F',
                        'stok' => $request->qty[$i]
                    ]);
                } else {
                    $stok->{'stok'} -= $request->qty[$i];
                    $stok->save();
                }
            }
        }

        return redirect()->route('retur-beli');
    }

    public function dataReturBeli($status, $id) {
        $retur = ReturBeli::All();
        $gudang = Gudang::where('retur', 'T')->get();

        $data = [
            'retur' => $retur,
            'gudang' => $gudang,
            'status' => $status,
            'id' => $id
        ];

        return view('pages.retur.indexBeli', $data);
    }

    public function showReturBeli(Request $request) {
        if($request->status == 'ALL')  {
            $status[0] = 'INPUT';
            $status[1] = 'LENGKAP';
            $status[2] = 'CETAK';
        }
        else {
            $status[0] = $request->status;
            $status[1] = '';
            $status[2] = '';
        }

        $awal = $request->tglAwal;
        if($awal == NULL)
            $awal = '0000-00-00';
        else
            $awal = $this->formatTanggal($awal, 'Y-m-d');


        $akhir = $request->tglAkhir;
        if($akhir == NULL)
            $akhir = '0000-00-00';
        else
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
            $retur = ReturJual::whereIn('status', [$status[0], $status[1], $status[2]])
                    ->where('tipe', 'Beli')->get();
        } else {
            $retur = ReturJual::whereIn('retur.status', [$status[0], $status[1], $status[2]])
                    ->where('tipe', 'Beli')
                    ->where(function ($q) use ($awal, $akhir, $month) {
                        $q->whereMonth('tanggal', $month)
                        ->orWhereBetween('tanggal', [$awal, $akhir]);
                    })->get();
        }

        $data = [
            'retur' => $retur,
            'bulan' => $request->bulan,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir,
            'status' => $request->status
        ];

        return view('pages.retur.showBeli', $data);
    }

    public function storeTerimaBeli(Request $request) {
        $lastcode = ReturTerima::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TRM'.sprintf("%04s", $lastnumber);

        $id = [];
        $gudang = Gudang::where('retur', 'T')->get();
        $retur = ReturBeli::where('id', $request->kode)->first();

        $items = DetilRB::where('id_retur', $request->kode)->get();
        foreach($items as $i) {
            if(($request->{"terima".$request->kode.$i->id_barang} != '') || ($request->{"batal".$request->kode.$i->id_barang} != '')) {
                $tglTerima = $request->{"tgl".$request->kode.$i->id_barang};
                $tglTerima = $this->formatTanggal($tglTerima, 'Y-m-d');

                $rt = ReturTerima::where('id', $newcode)->get();
                if($rt->count() == 0) {
                    ReturTerima::create([
                        'id' => $newcode,
                        'id_retur' => $request->kode,
                        'tanggal' => $tglTerima
                    ]);

                    array_push($id, $newcode);
                } 
                elseif($rt->last()->tanggal != $tglTerima) {
                    $lastcode = ReturTerima::max('id');
                    $lastnumber = (int) substr($lastcode, 3, 4);
                    $lastnumber++;
                    $newcode = 'TRM'.sprintf("%04s", $lastnumber);

                    ReturTerima::create([
                        'id' => $newcode,
                        'id_retur' => $request->kode,
                        'tanggal' => $tglTerima
                    ]);

                    array_push($id, $newcode);
                }

                DetilRT::create([
                    'id_terima' => $newcode,
                    'id_barang' => $i->id_barang,
                    'qty_terima' => $request->{"terima".$request->kode.$i->id_barang},
                    'qty_batal' => $request->{"batal".$request->kode.$i->id_barang}
                ]);

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'T')->first();
                $stokBagus->{'stok'} += (int) $request->{"terima".$request->kode.$i->id_barang}; 
                $stokBagus->save();

                $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'F')->first();
                $stokJelek->{'stok'} += (int) $request->{"batal".$request->kode.$i->id_barang};
                $stokJelek->save();
            }
        }

        $items = DetilRB::selectRaw('sum(qty_retur) as total')
                ->where('id_retur', $request->kode)->get();
        $total = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                ->select(DB::raw('sum(qty_terima) as totalTerima, 
                sum(qty_batal) as totalBatal'))->where('id_retur', $request->kode)->get();

        if($items[0]->total == ($total[0]->totalTerima + $total[0]->totalBatal)) 
            $status = 'LENGKAP';
        else 
            $status = 'INPUT';

        $retur->{'status'} = $status;
        $retur->save();

        $query = http_build_query(array('id' => $id));

        $data = [
            'status' => 'true',
            'id' => $query,
        ];

        return redirect()->route('retur-beli', $data);
    }

    public function cetakTerimaBeli(Request $request, $id) {
        parse_str($id, $kode);

        $items = ReturTerima::whereIn('id', $kode['id'])->get();
        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        $paper = array(0,0,612,394);
        $pdf = PDF::loadview('pages.retur.cetakBeli', $data)->setPaper($paper);
        ob_end_clean();
        return $pdf->stream('cetak-bm.pdf');
    }
}
