<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\StokBarang;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\Retur;
use App\Models\DetilRetur;
use App\Models\DetilRJ;
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use App\Models\DetilRB;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $so = SalesOrder::All();

        $data = [
            'tanggal' => $tanggal,
            'so' => $so
        ];

        return view('pages.retur.createJual', $data);
    }

    public function showCreateJual(Request $request) {
        $items = DetilSO::select('id_so', 'id_barang', DB::raw('sum(qty) as qty'))
                ->where('id_so', $request->kode)->groupBy('id_barang')->get();
        $so = SalesOrder::All();

        $data = [
            'items' => $items,
            'tanggal' => $request->tanggal,
            'so' => $so
        ];

        return view('pages.retur.detailJual', $data);
    }

    public function storeJual(Request $request, $id) {
        $gudang = Gudang::where('retur', 'T')->get();
        $items = DetilSO::select('id_so', 'id_barang', DB::raw('sum(qty) as qty'))
                ->where('id_so', $request->kode)->groupBy('id_barang')->get();
        $tanggal = $this->formatTanggal($request->tanggal, 'Y-m-d');

        $lastcode = Retur::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'RET'.sprintf('%04s', $lastnumber);

        Retur::create([
            'id' => $newcode,
            'tanggal' => $tanggal,
            'id_faktur' => $id,
            'tipe' => 'Jual',
            'status' => 'INPUT'
        ]);

        $i = 0;
        foreach($items as $item) {
            if($request->qty[$i] != '') {
                DetilRetur::create([
                    'id_retur' => $newcode,
                    'id_barang' => $item->id_barang,
                    'qty' => $request->qty[$i]
                ]);

                $stok = StokBarang::where('id_barang', $item->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'F')->first();
                
                if($stok == NULL) {
                    StokBarang::create([
                        'id_barang' => $item->id_barang,
                        'id_gudang' => $gudang[0]->id,
                        'status' => 'F',
                        'stok' => $request->qty[$i]
                    ]);
                } else {
                    $stok->{'stok'} += $request->qty[$i];
                    $stok->save();
                }
            }
            
            $i++;
        }

        return redirect()->route('retur-stok');
    }

    public function dataReturJual() {
        $retur = Retur::where('tipe', 'Jual')->get();

        $data = [
            'retur' => $retur
        ];

        return view('pages.retur.indexJual', $data);
    }

    public function storeKirimJual(Request $request) {
        $lastcode = DetilRJ::max('id_kirim');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'KRM'.sprintf("%04s", $lastnumber);

        $gudang = Gudang::where('retur', 'T')->get();
        $retur = Retur::where('id', $request->kode)->first();

        $totRetur = 0;
        $items = DetilRetur::where('id_retur', $request->kode)->get();
        foreach($items as $i) {
            if(($request->{"kirim".$request->kode.$i->id_barang} != '') || ($request->{"batal".$request->kode.$i->id_barang} != '')) {
                $tglKirim = $request->{"tgl".$request->kode.$i->id_barang};
                $tglKirim = $this->formatTanggal($tglKirim, 'Y-m-d');

                DetilRJ::create([
                    'id_retur' => $request->kode,
                    'id_barang' => $i->id_barang,
                    'id_kirim' => $newcode,
                    'tgl_kirim' => $tglKirim,
                    'qty_kirim' => $request->{"kirim".$request->kode.$i->id_barang},
                    'qty_batal' => $request->{"batal".$request->kode.$i->id_barang}
                ]);

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'T')->first();
                $stokBagus->{'stok'} -= (int) $request->{"kirim".$request->kode.$i->id_barang}; 
                $stokBagus->save();

                // $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                //         ->where('id_gudang', $gudang[0]->id)
                //         ->where('status', 'F')->first();
                // $stokJelek->{'stok'} += (int) $request->{"batal".$request->kode.$i->id_barang};
                // $stokJelek->save();
            }
        }

        $items = DetilRetur::selectRaw('sum(qty) as total')->where('id_retur', $request->kode)
                ->get();
        $total = DetilRJ::select(DB::raw('sum(qty_kirim) as totalKirim, 
                sum(qty_batal) as totalBatal'))->where('id_retur', $request->kode)->get();

        if($items[0]->total == ($total[0]->totalKirim + $total[0]->totalBatal)) 
            $status = 'LENGKAP';
        else 
            $status = 'INPUT';

        $retur->{'status'} = $status;
        $retur->save();

        return redirect()->route('retur-jual');
    }

    public function createPembelian() {
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $bm = BarangMasuk::All();

        $data = [
            'tanggal' => $tanggal,
            'bm' => $bm
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
        $items = DetilBM::select('id_bm', 'id_barang', DB::raw('sum(qty) as qty'))
                ->where('id_bm', $request->kode)->groupBy('id_barang')->get();
        $tanggal = $this->formatTanggal($request->tanggal, 'Y-m-d');

        $lastcode = Retur::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'RET'.sprintf('%04s', $lastnumber);

        Retur::create([
            'id' => $newcode,
            'tanggal' => $tanggal,
            'id_faktur' => $id,
            'tipe' => 'Beli',
            'status' => 'INPUT'
        ]);

        $i = 0;
        foreach($items as $item) {
            if($request->qty[$i] != '') {
                DetilRetur::create([
                    'id_retur' => $newcode,
                    'id_barang' => $item->id_barang,
                    'qty' => $request->qty[$i]
                ]);

                $stok = StokBarang::where('id_barang', $item->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'F')->first();
                
                if($stok == NULL) {
                    StokBarang::create([
                        'id_barang' => $item->id_barang,
                        'id_gudang' => $gudang[0]->id,
                        'status' => 'F',
                        'stok' => $request->qty[$i]
                    ]);
                } else {
                    $stok->{'stok'} -= $request->qty[$i];
                    $stok->save();
                }
            }
            
            $i++;
        }

        return redirect()->route('retur-stok');
    }

    public function dataReturBeli() {
        $retur = Retur::where('tipe', 'Beli')->get();

        $data = [
            'retur' => $retur
        ];

        return view('pages.retur.indexBeli', $data);
    }

    public function storeTerimaBeli(Request $request) {
        $lastcode = DetilRB::max('id_terima');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TRM'.sprintf("%04s", $lastnumber);

        $gudang = Gudang::where('retur', 'T')->get();
        $retur = Retur::where('id', $request->kode)->first();

        $totRetur = 0;
        $items = DetilRetur::where('id_retur', $request->kode)->get();
        foreach($items as $i) {
            if(($request->{"terima".$request->kode.$i->id_barang} != '') || ($request->{"batal".$request->kode.$i->id_barang} != '')) {
                $tglTerima = $request->{"tgl".$request->kode.$i->id_barang};
                $tglTerima = $this->formatTanggal($tglTerima, 'Y-m-d');

                DetilRB::create([
                    'id_retur' => $request->kode,
                    'id_barang' => $i->id_barang,
                    'id_terima' => $newcode,
                    'tgl_terima' => $tglTerima,
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

        $items = DetilRetur::selectRaw('sum(qty) as total')->where('id_retur', $request->kode)
                ->get();
        $total = DetilRB::select(DB::raw('sum(qty_terima) as totalTerima, 
                sum(qty_batal) as totalBatal'))->where('id_retur', $request->kode)->get();

        if($items[0]->total == ($total[0]->totalTerima + $total[0]->totalBatal)) 
            $status = 'LENGKAP';
        else 
            $status = 'INPUT';

        $retur->{'status'} = $status;
        $retur->save();

        return redirect()->route('retur-beli');
    }
}
