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
use App\Models\NeedApproval;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReturController extends Controller
{
    public function index() {
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $items = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                ->where('tipe', 'RETUR')->groupBy('id_barang')->get();

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

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = ReturJual::selectRaw('max(id) as id')->whereMonth('tanggal', $month)
                    ->where('id', 'LIKE', 'RTJ%')->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 4);
        $lastnumber++;
        $newcode = 'RTJ'.$tahun.$bulan.sprintf('%04s', $lastnumber);

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
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $tanggal = $this->formatTanggal($request->tanggal, 'Y-m-d');

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = ReturJual::selectRaw('max(id) as id')->whereMonth('tanggal', $month)
                    ->where('id', 'LIKE', 'RTJ%')->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 4);
        $lastnumber++;
        $newcode = 'RTJ'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        ReturJual::create([
            'id' => $newcode,
            'tanggal' => $tanggal,
            'id_customer' => $request->kodeCustomer,
            'status' => 'INPUT'
        ]);

        for($i = 0; $i < $request->jumBaris; $i++) {
            if($request->kodeBarang[$i] != '') {
                DetilRJ::create([
                    'id_retur' => $newcode,
                    'id_barang' => $request->kodeBarang[$i],
                    'tgl_kirim' => NULL,
                    'qty_retur' => $request->qty[$i],
                    'qty_kirim' => NULL,
                    'potong' => NULL
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

    public function batalReturJual(Request $request, $id) {
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $detilRJ = DetilRJ::where('id_retur', $id)->get();

        foreach($detilRJ as $d) {
            $stok = StokBarang::where('id_barang', $d->id_barang)
                    ->where('id_gudang', $gudang->first()->id)
                    ->where('status', 'F')->first();

            $stok->{'stok'} -= $d->qty_retur;
            $stok->save();

            $stokBagus = StokBarang::where('id_barang', $d->id_barang)
                    ->where('id_gudang', $gudang->first()->id)
                    ->where('status', 'T')->first();

            $stokBagus->{'stok'} += $d->qty_kirim;
            $stokBagus->save();
        }

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'PENDING_BATAL',
            'keterangan' => $request->keterangan,
            'id_dokumen' => $id,
            'tipe' => 'RJ',
            'id_user' => Auth::user()->id
        ]);

        $rj = ReturJual::where('id', $id)->first();
        $rj->{'status'} = 'PENDING_BATAL';
        $rj->save();

        $data = [
            'status' => 'false',
            'id' => '0'
        ];

        return redirect()->route('retur-jual', $data);
    }

    public function dataReturJual($status, $id) {
        $retur = ReturJual::where('status', '!=', 'BATAL')->orderBy('tanggal', 'desc')->get();
        $gudang = Gudang::where('tipe', 'RETUR')->get();

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
        }
        else {
            $status[0] = $request->status;
            $status[1] = '';
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
            $retur = ReturJual::whereIn('status', [$status[0], $status[1]])->orderBy('id', 'desc')->get();
        } else {
            $retur = ReturJual::whereIn('status', [$status[0], $status[1]])
                    ->where(function ($q) use ($awal, $akhir, $month) {
                        $q->whereMonth('tanggal', $month)
                        ->orWhereBetween('tanggal', [$awal, $akhir]);
                    })->orderBy('id', 'desc')->get();
        }

        $gudang = Gudang::where('tipe', 'RETUR')->get();

        $data = [
            'retur' => $retur,
            'gudang' => $gudang,
            'bulan' => $request->bulan,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir,
            'status' => $request->status
        ];

        return view('pages.retur.showJual', $data);
    }

    public function createReturJual($id) {
        $item = ReturJual::where('id', $id)->get();
        $retur = DetilRJ::where('id_retur', $id)->get();
        $barang = Barang::All();
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $stokBagus = StokBarang::where('id_gudang', $gudang->first()->id)->where('status', 'T')->get();

        $data = [
            'item' => $item,
            'retur' => $retur,
            'barang' => $barang,
            'gudang' => $gudang,
            'stokBagus' => $stokBagus
        ];

        return view('pages.retur.kirimJualNew', $data);
    }

    public function storeKirimJual(Request $request) {
        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = DetilRJ::selectRaw('max(id_kirim) as id')->whereYear('tgl_kirim', $waktu->year)
                    ->whereMonth('tgl_kirim', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'KRM'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $retur = ReturJual::where('id', $request->kode)->first();
        $items = DetilRJ::where('id_retur', $request->kode)->get();

        $kodeBarang = []; 
        if($items->count() != $request->jumBaris) {
            for($i = 0; $i < $request->jumBaris; $i++) {
                array_push($kodeBarang, $request->kodeBarang[$i]);
            }

            $hapus = DetilRJ::where('id_retur', $request->kode)
                    ->whereNotIn('id_barang', $kodeBarang)->get();

            foreach($hapus as $i) {
                $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'F')->first();
                $stokJelek->{'stok'} -= $i->qty_retur;
                $stokJelek->save();

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'T')->first();
                $stokBagus->{'stok'} += $i->qty_kirim;
                $stokBagus->save();
            }

            DetilRJ::where('id_retur', $request->kode)->whereNotIn('id_barang', $kodeBarang)->delete();
        } 

        $items = DetilRJ::where('id_retur', $request->kode)->get();
        $k = 0; $qtyAwal = 0; $qtyRetur = 0;
        foreach($items as $i) {
            if($request->tgl[$k] != '') {
                $tglKirim = $request->tgl[$k];
                $tglKirim = $this->formatTanggal($tglKirim, 'Y-m-d');
            } else {
                $tglKirim = NULL;
            }

            if(($request->kodeBarang[$k] != $i->id_barang) || ($request->qtyRetur[$k] != $i->qty_retur) || ($tglKirim != $i->tgl_kirim) || ($request->kirim[$k] != $i->qty_kirim)) {
                if($request->kodeBarang[$k] != $i->id_barang) {
                    $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                            ->where('id_gudang', $gudang->first()->id)
                            ->where('status', 'F')->first();
                    $stokJelek->{'stok'} -= $i->qty_retur; 
                    $stokJelek->save();

                    $stokJelekNew = StokBarang::where('id_barang', $request->kodeBarang[$k])
                            ->where('id_gudang', $gudang->first()->id)
                            ->where('status', 'F')->first();

                    if($stokJelekNew == NULL) {
                        StokBarang::create([
                            'id_barang' => $request->kodeBarang[$k],
                            'id_gudang' => $gudang->first()->id,
                            'status' => 'F',
                            'stok' => $request->qtyRetur[$k]
                        ]);
                    } else {
                        $stokJelekNew->{'stok'} += $request->qtyRetur[$k]; 
                    }
                    $stokJelekNew->save();

                    $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                            ->where('id_gudang', $gudang->first()->id)
                            ->where('status', 'T')->first();
                    $stokBagus->{'stok'} += $i->qty_kirim; 
                    $stokBagus->save();

                    $stokBagusNew = StokBarang::where('id_barang', $request->kodeBarang[$k])
                                ->where('id_gudang', $gudang->first()->id)
                                ->where('status', 'T')->first();
                    if($stokBagusNew == NULL) {
                        StokBarang::create([
                            'id_barang' => $request->kodeBarang[$k],
                            'id_gudang' => $gudang->first()->id,
                            'status' => 'T',
                            'stok' => $request->kirim[$k]
                        ]);
                    } else {
                        $stokBagusNew->{'stok'} -= $request->kirim[$k]; 
                    }
                    $stokBagusNew->save();
                } else {
                    if($request->qtyRetur[$k] != $i->qty_retur) {
                        $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                                ->where('id_gudang', $gudang->first()->id)
                                ->where('status', 'F')->first();

                        if($i->qty_retur > $request->qtyRetur[$k])
                            $stokJelek->{'stok'} -= ($i->qty_retur - $request->qtyRetur[$k]);
                        else
                            $stokJelek->{'stok'} += ($request->qtyRetur[$k] - $i->qty_retur);

                        $stokJelek->save();
                    }
                }

                $qty = $i->qty_kirim;
                if($i->id_kirim == NULL)
                    $i->id_kirim = $newcode;

                $i->id_barang = $request->kodeBarang[$k];
                $i->tgl_kirim = $tglKirim;
                $i->qty_retur = $request->qtyRetur[$k];
                $i->qty_kirim = $request->kirim[$k];
                $i->save();

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'T')->first();
                
                if($qty == NULL)
                    $stokBagus->{'stok'} -= $request->kirim[$k]; 
                else {
                    if($qty > $request->kirim[$k])
                        $stokBagus->{'stok'} += ($qty - $request->kirim[$k]);
                    else
                        $stokBagus->{'stok'} -= ($request->kirim[$k] - $qty);
                }
                 
                $stokBagus->save();
            }
            $qtyAwal += $i->qty_retur;
            $qtyRetur += $i->qty_kirim;
            $k++;
        }

        if($qtyAwal == $qtyRetur) {
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
        $items = ReturJual::join('detilrj', 'detilrj.id_retur', 'returjual.id')
                ->where('id_kirim', $id)->groupBy('id_kirim')->get();
        // $items = DetilRJ::where('id_kirim', $id)->get();
        $tabel = ceil($items->first()->detilrj->count() / 12);

        if($tabel > 1) {
            for($i = 1; $i < $tabel; $i++) {
                $item = collect([
                    'id' => $items->first()->id,
                    'tanggal' => $items->first()->tanggal,
                    'id_customer' => $items->first()->id_customer,
                    'status' => $items->first()->status
                ]);

                $items->push($item);
            }
        }
        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.retur.cetakJual', $data);

        // $paper = array(0,0,686,394);
        // $pdf = PDF::loadview('pages.retur.cetakJual', $data)->setPaper($paper);
        // ob_end_clean();
        // return $pdf->stream('cetak-so.pdf');
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
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $barang = StokBarang::where('id_gudang', $gudang[0]->id)
                ->where('status', 'F')->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = ReturBeli::selectRaw('max(id) as id')->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 4);
        $lastnumber++;
        $newcode = 'RTB'.$tahun.$bulan.sprintf('%04s', $lastnumber);

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
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $tanggal = $this->formatTanggal($request->tanggal, 'Y-m-d');

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = ReturBeli::selectRaw('max(id) as id')->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 4);
        $lastnumber++;
        $newcode = 'RTB'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        ReturBeli::create([
            'id' => $newcode,
            'tanggal' => $tanggal,
            'id_supplier' => $request->kodeSupplier,
            'status' => 'INPUT'
        ]);

        for($i = 0; $i < $request->jumBaris; $i++) {
            if($request->kodeBarang[$i] != '') {
                DetilRB::create([
                    'id_retur' => $newcode,
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

        $data = [
            'status' => 'false',
            'id' => '0'
        ];

        return redirect()->route('retur-beli', $data);
    }

    public function batalReturBeli(Request $request, $id) {
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $detilRB = DetilRB::where('id_retur', $id)->get();

        foreach($detilRB as $d) {
            $stok = StokBarang::where('id_barang', $d->id_barang)
                    ->where('id_gudang', $gudang->first()->id)
                    ->where('status', 'F')->first();

            $stok->{'stok'} += $d->qty_retur;

            $rt = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                    ->selectRaw('sum(qty_terima) as qt, sum(qty_batal) as qb, sum(potong) as qp')
                    ->where('id_retur', $id)->where('id_barang', $d->id_barang)
                    ->groupBy('id_barang')->get();

            $stokBagus = StokBarang::where('id_barang', $d->id_barang)
                    ->where('id_gudang', $gudang->first()->id)
                    ->where('status', 'T')->first();
            if($rt->count() != 0) {
                $stokBagus->{'stok'} -= $rt->first()->qt;
                $stokBagus->save();

                $stok->{'stok'} -= ($rt->first()->qb + $rt->first()->qp);
            }

            $stok->save();
        }

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'PENDING_BATAL',
            'keterangan' => $request->keterangan,
            'id_dokumen' => $id,
            'tipe' => 'RB',
            'id_user' => Auth::user()->id
        ]);

        $rb = ReturBeli::where('id', $id)->first();
        $rb->{'status'} = 'PENDING_BATAL';
        $rb->save();

        $data = [
            'status' => 'false',
            'id' => '0'
        ];

        return redirect()->route('retur-beli', $data);
    }

    public function dataReturBeli($status, $id) {
        $retur = ReturBeli::where('status', '!=', 'BATAL')->orderBy('id', 'desc')->get();
        $gudang = Gudang::where('tipe', 'RETUR')->get();

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
        }
        else {
            $status[0] = $request->status;
            $status[1] = '';
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
            $retur = ReturBeli::whereIn('status', [$status[0], $status[1]])->orderBy('id', 'desc')->get();
        } else {
            $retur = ReturBeli::whereIn('status', [$status[0], $status[1]])
                    ->where(function ($q) use ($awal, $akhir, $month) {
                        $q->whereMonth('tanggal', $month)
                        ->orWhereBetween('tanggal', [$awal, $akhir]);
                    })->orderBy('id', 'desc')->get();
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

    public function createReturBeli($id) {
        $item = ReturBeli::where('id', $id)->get();
        $retur = DetilRB::where('id_retur', $id)->get();
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $barang = Barang::All();

        $data = [
            'item' => $item,
            'retur' => $retur,
            'gudang' => $gudang,
            'barang' => $barang
        ];

        return view('pages.retur.kirimBeliNew', $data);
    }

    public function storeTerimaBeli(Request $request) {
        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = ReturTerima::selectRaw('max(id) as id')
                    ->whereYear('tanggal', $waktu->year)->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'TRM'.$tahun.$bulan.sprintf("%04s", $lastnumber);

        $id = [];
        $gudang = Gudang::where('tipe', 'RETUR')->get();
        $retur = ReturBeli::where('id', $request->kode)->first();

        $items = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                ->where('id_retur', $request->kode)->orderBy('id_barang')->orderBy('id_terima')->get();
        $rb = DetilRB::where('id_retur', $request->kode)->get();
        $jum = $request->jumBaris - $request->jumRB;

        $kodeBarang = []; $kodeTerima = [];
        if($items->count() != 0) {
            if($jum != $items->count()) {
                $k = 0;
                foreach($items as $i) {
                    if(($k >= $jum) || ($i->id_terima != $request->kodeTerima[$k]) && ($i->id_barang != $request->kodeDetil[$k])) {
                        $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                                ->where('id_gudang', $gudang->first()->id)
                                ->where('status', 'F')->first();
                        $stokJelek->{'stok'} -= $i->qty_batal;
                        $stokJelek->save();

                        $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                                ->where('id_gudang', $gudang->first()->id)
                                ->where('status', 'T')->first();
                        $stokBagus->{'stok'} -= $i->qty_terima;
                        $stokBagus->save();

                        DetilRT::where('id_terima', $i->id_terima)->where('id_barang', $i->id_barang)->delete();
                    } else {
                        $k++;
                    }
                }
                array_push($id, '0');
                $stat = 0;
            }
        } else {
            if($request->jumBaris != $rb->count()) {
                for($i = 0; $i < $request->jumBaris; $i++) {
                    array_push($kodeBarang, $request->kodeBarang[$i]);
                }

                $hapus = DetilRB::where('id_retur', $request->kode)
                        ->whereNotIn('id_barang', $kodeBarang)->get();

                foreach($hapus as $i) {
                    $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                            ->where('id_gudang', $gudang->first()->id)
                            ->where('status', 'F')->first();
                    $stokJelek->{'stok'} += $i->qty_retur;
                    $stokJelek->save();
                }

                DetilRB::where('id_retur', $request->kode)->whereNotIn('id_barang', $kodeBarang)->delete();
                array_push($id, '0');
                $stat = 0;
            }
        }
                
        $items = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                ->where('id_retur', $request->kode)->orderBy('id_barang')->orderBy('id_terima')->get();
        $d = 0; $stat = 0;
        foreach($items as $i) {
            if($request->tglDetil[$d] != '') {
                $tglTerima = $request->tglDetil[$d];
                $tglTerima = $this->formatTanggal($tglTerima, 'Y-m-d');
            } else {
                $tglTerima = NULL;
            }
            
            if(($tglTerima != $i->returterima->tanggal) || ($request->terimaDetil[$d] != $i->qty_terima) || ($request->batalDetil[$d] != $i->qty_batal)) {
                $rt = ReturTerima::where('id', $i->id)->where('id_retur', $request->kode)->first();
                $rt->{'tanggal'} = $tglTerima;
                $rt->save();

                $qtyTer = $i->qty_terima;
                $qtyBat = $i->qty_batal;
  
                // $i->id_barang = $request->kodeBarang[$k];
                $i->qty_terima = $request->terimaDetil[$d];
                $i->qty_batal = $request->batalDetil[$d];
                $i->save();

                array_push($id, $i->id_terima);

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'T')->first();

                if($qtyTer > $request->terimaDetil[$d])
                    $stokBagus->{'stok'} -= ($qtyTer - $request->terimaDetil[$d]);
                else
                    $stokBagus->{'stok'} += ($request->terimaDetil[$d] - $qtyTer);
                
                $stokBagus->save();

                $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'F')->first();

                if($qtyBat > $request->batalDetil[$d])
                    $stokJelek->{'stok'} -= ($qtyBat - $request->batalDetil[$d]);
                else
                    $stokJelek->{'stok'} += ($request->batalDetil[$d] - $qtyBat);
                
                $stokJelek->save();

                $stat = 1;
            }

            $d++;
        }

        $items = DetilRB::where('id_retur', $request->kode)->get();
        $t = 0;
        foreach($items as $i) {
            if($request->kodeBarang[$t] != $i->id_barang) {
                $rt = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                            ->select('id_terima', 'id_barang')->selectRaw('sum(qty_batal) as totBatal, sum(qty_terima) as totTerima')
                            ->where('id_retur', $request->kode)->where('id_barang', $i->id_barang)->get();

                $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'F')->first();
                $stokJelek->{'stok'} += ($i->qty_retur - $rt->first()->totBatal); 
                $stokJelek->save();

                $stokJelekNew = StokBarang::where('id_barang', $request->kodeBarang[$t])
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'F')->first();

                if($stokJelekNew == NULL) {
                    StokBarang::create([
                        'id_barang' => $request->kodeBarang[$t],
                        'id_gudang' => $gudang->first()->id,
                        'status' => 'F',
                        'stok' => $request->qty[$t] - $rt->first()->totBatal
                    ]);
                } else {
                    $stokJelekNew->{'stok'} -= ($request->qty[$t] - $rt->first()->totBatal); 
                }
                $stokJelekNew->save();

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang->first()->id)
                        ->where('status', 'T')->first();
                $stokBagus->{'stok'} -= $rt->first()->totTerima; 
                $stokBagus->save();

                $stokBagusNew = StokBarang::where('id_barang', $request->kodeBarang[$t])
                            ->where('id_gudang', $gudang->first()->id)
                            ->where('status', 'T')->first();
                if($stokBagusNew == NULL) {
                    StokBarang::create([
                        'id_barang' => $request->kodeBarang[$t],
                        'id_gudang' => $gudang->first()->id,
                        'status' => 'T',
                        'stok' => $rt->first()->totTerima
                    ]);
                } else {
                    $stokBagusNew->{'stok'} += $rt->first()->totTerima; 
                }

                $stokBagusNew->save();

                $barangRT = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                            ->where('id_retur', $request->kode)->where('id_barang', $i->id_barang)->get();
                if($barangRT->count() != 0) {
                    foreach($barangRT as $br) {
                        $br->id_barang = $request->kodeBarang[$t];
                        $br->save();
                        array_push($id, $br->id_terima);
                        $stat = 1;
                    }
                } else {
                    array_push($id, '0');
                    $stat = 0;
                }

                $i->id_barang = $request->kodeBarang[$t];
                $i->qty_retur = $request->qty[$t];
                $i->save();
            } else { 
                if($request->qty[$t] != $i->qty_retur) {
                    $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                            ->where('id_gudang', $gudang->first()->id)
                            ->where('status', 'F')->first();

                    if($i->qty_retur > $request->qty[$t])
                        $stokJelek->{'stok'} += ($i->qty_retur - $request->qty[$t]);
                    else
                        $stokJelek->{'stok'} -= ($request->qty[$t] - $i->qty_retur);

                    $stokJelek->save();

                    $i->qty_retur = $request->qty[$t];
                    $i->save();

                    if($stat == 0)
                        array_push($id, '0');
                }
            }

            if(($request->terima[$t] != '') || ($request->batal[$t] != '')) {
                $tglTerima = $request->tgl[$t];
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
                    $lastcode = ReturTerima::selectRaw('max(id) as id')->whereYear('tanggal', $waktu->year)
                                ->whereMonth('tanggal', $month)->get();
                    $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
                    $lastnumber++;
                    $newcode = 'TRM'.$tahun.$bulan.sprintf("%04s", $lastnumber);

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
                    'qty_terima' => $request->terima[$t],
                    'qty_batal' => $request->batal[$t],
                    'potong' => NULL
                ]);

                $stokBagus = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'T')->first();
                $stokBagus->{'stok'} += (int) $request->terima[$t]; 
                $stokBagus->save();

                $stokJelek = StokBarang::where('id_barang', $i->id_barang)
                        ->where('id_gudang', $gudang[0]->id)
                        ->where('status', 'F')->first();
                $stokJelek->{'stok'} += (int) $request->batal[$t];
                $stokJelek->save();

                $stat = 1;
            }

            $t++;
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

        if($stat == 0) 
            $statIndex = 'false';
        else
            $statIndex = 'true';

        $data = [
            'status' => $statIndex,
            'id' => $query,
        ];

        return redirect()->route('retur-beli', $data);
    }

    public function potongTagihanBeli($id) {
        $lastcode = ReturTerima::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TRM'.sprintf("%04s", $lastnumber);

        $items = DetilRB::where('id_retur', $id)->get();
        // $terima = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
        //         ->selectRaw('sum(qty_terima) as qty_terima')->selectRaw('sum(qty_batal) as qty_batal')
        //         ->where('id_retur', $id)->groupBy('id_barang')->get();

        $t = 0; $qty = 0;
        foreach($items as $i) {
            $tglTerima = Carbon::now()->toDateString();
            // $tglTerima = $this->formatTanggal($tglTerima, 'Y-m-d');

            $terima = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                ->selectRaw('sum(qty_terima) as qty_terima')->selectRaw('sum(qty_batal) as qty_batal')
                ->where('id_retur', $id)->where('id_barang', $i->id_barang)->get();

            $rt = ReturTerima::where('id', $newcode)->get();
            if($rt->count() == 0) {
                ReturTerima::create([
                    'id' => $newcode,
                    'id_retur' => $id,
                    'tanggal' => $tglTerima
                ]);
            } 
            elseif($rt->last()->tanggal != $tglTerima) {
                $lastcode = ReturTerima::max('id');
                $lastnumber = (int) substr($lastcode, 3, 4);
                $lastnumber++;
                $newcode = 'TRM'.sprintf("%04s", $lastnumber);

                ReturTerima::create([
                    'id' => $newcode,
                    'id_retur' => $id,
                    'tanggal' => $tglTerima
                ]);
            }

            if($terima->count() != 0) 
                $qty = $i->qty_retur - $terima[0]->qty_terima - $terima[0]->qty_batal;

            if($qty != 0) {
                DetilRT::create([
                    'id_terima' => $newcode,
                    'id_barang' => $i->id_barang,
                    'qty_terima' => NULL,
                    'qty_batal' => NULL,
                    'potong' => $qty
                ]);
            }

            $t++;
        }

        $retur = ReturBeli::where('id', $id)->first();
        $retur->{'status'} = 'LENGKAP';
        $retur->save();

        $data = [
            'status' => 'false',
            'id' => '0',
        ];

        return redirect()->route('retur-beli', $data);
    }

    public function cetakTerimaBeli(Request $request, $id) {
        parse_str($id, $kode);

        $items = ReturTerima::whereIn('id', $kode['id'])->get();
        $tabel = ceil($items->first()->detilrt->count() / 8);

        if($tabel > 1) {
            for($i = 1; $i < $tabel; $i++) {
                $item = collect([
                    'id' => $items->first()->id,
                    'id_retur' => $items->first()->id_retur,
                    'tanggal' => $items->first()->tanggal
                ]);

                $items->push($item);
            }
        }
        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.retur.cetakBeli', $data);

        // $paper = array(0,0,612,394);
        // $pdf = PDF::loadview('pages.retur.cetakBeli', $data)->setPaper($paper);
        // ob_end_clean();
        // return $pdf->stream('cetak-bm.pdf');
    }
}
