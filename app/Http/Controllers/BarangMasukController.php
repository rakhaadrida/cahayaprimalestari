<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\HargaBarang;
use App\Models\BarangMasuk;
use App\Models\DetilBM;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\AccPayable;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    public function index() {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $gudang = Gudang::All();

        // autonumber
        $lastcode = BarangMasuk::max('id');
        $lastnumber = (int) substr($lastcode, 2, 4);
        $lastnumber++;
        $newcode = 'BM'.sprintf('%04s', $lastnumber);

        // $items = TempDetilBM::with(['barang', 'supplier'])->where('id_bm', $newcode)
        //             ->orderBy('created_at','asc')->get();
        // $itemsRow = TempDetilBM::where('id_bm', $newcode)->count();

        // date now
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            // 'items' => $items,
            // 'itemsRow' => $itemsRow,
            'supplier' => $supplier,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'gudang' => $gudang
        ];

        return view('pages.pembelian.barangmasuk.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    /* public function create(Request $request, $id) {
        TempDetilBM::create([
            'id_bm' => $id,
            'id_barang' => $request->kodeBarang,
            'harga' => $request->harga,
            'qty' => $request->pcs,
            'keterangan' => $request->ket,
            'id_supplier' => $request->kodeSupplier
        ]);

        return redirect()->route('barangMasuk');
    } */

    public function process (Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;
        
        BarangMasuk::create([
            'id' => $id,
            'tanggal' => $tanggal,
            'total' => str_replace(".", "", $request->subtotal),
            'id_gudang' => $request->kodeGudang,
            'id_supplier' => $request->kodeSupplier,
            'status' => 'NO_DISC'
        ]);

        $lastcode = AccPayable::max('id');
        $lastnumber = (int) substr($lastcode, 2, 4);
        $lastnumber++;
        $newcode = 'AP'.sprintf('%04s', $lastnumber);

        AccPayable::create([
            'id' => $newcode, 
            'id_bm' => $id,
            'keterangan' => "BELUM LUNAS"
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                DetilBM::create([
                    'id_bm' => $request->id,
                    'id_barang' => $request->kodeBarang[$i],
                    'harga' => str_replace(".", "", $request->harga[$i]),
                    'qty' => $request->qty[$i],
                    'diskon' => NULL,
                    'hpp' => NULL,
                ]);

                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                            ->where('id_gudang', $request->kodeGudang)->first();
                $updateStok->{'stok'} = $updateStok->{'stok'} + $request->qty[$i];
                $updateStok->save();
            }
        }

        /*
        $tempDetil = TempDetilBM::where('id_bm', $id)->get();
        foreach($tempDetil as $td) {
            DetilBM::create([
                'id_bm' => $td->id_bm,
                'id_barang' => $td->id_barang,
                'harga' => str_replace(".", "", $td->harga),
                'qty' => $td->qty,
                'keterangan' => $td->keterangan
            ]);

            $updateStok = StokBarang::where('id_barang', $td->id_barang)
                            ->where('id_gudang', 'GDG01')->first();
            $updateStok->{'stok'} = $updateStok->{'stok'} + $td->qty;
            $updateStok->save();

            $deleteTemp = TempDetilBM::where('id_bm', $id)->where('id_barang', $td->id_barang)->delete();
        }
        */

        return redirect()->route('barangMasuk');
    }

    /* public function update(Request $request, $bm, $barang, $id) {
        $updateDetil = TempDetilBM::where('id_bm', $bm)->where('id_barang', $barang)->first();

        $updateDetil->{'qty'} = $request->editQty[$id-1];
        $updateDetil->{'keterangan'} = $request->editKet[$id-1];
        $updateDetil->save();

        return redirect()->route('barangMasuk');
    } 

    public function remove($bm, $barang) {
        $tempDetil = TempDetilBM::where('id_bm', $bm)->where('id_barang', $barang)->delete();

        return redirect()->route('barangMasuk');
    } 

    public function reset($bm) {
         $tempDetil = TempDetilBM::where('id_bm', $bm)->delete();

        return redirect()->route('barangMasuk');
    } */

    public function change() {
        $bm = BarangMasuk::All();
        $supplier = Supplier::All();

        $data = [
            'bm' => $bm,
            'supplier' => $supplier
        ];

        return view('pages.pembelian.ubahBM.index', $data);
    }

    public function show(Request $request) {
        $tglAwal = $request->tglAwal;
        $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
        $tglAkhir = $request->tglAkhir;
        $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');

        $isi = 1;
        if(($request->kode != '') && ($request->tglAwal != '') && ($request->tglAkhir != ''))
            $isi = 2;

        if($isi == 1) {
            $items = BarangMasuk::with(['supplier', 'gudang'])->where('id', $request->id)
                    ->orWhere('id_supplier', $request->kode)
                    ->orWhereBetween('tanggal', [$tglAwal, $tglAkhir])
                    ->orderBy('id', 'asc')->get();
        } else {
            $items = BarangMasuk::with(['supplier', 'gudang'])
                    ->where('id_supplier', $request->kode)
                    ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                    ->orWhere('id', $request->id)
                    ->orderBy('id', 'asc')->get();
        }
        
        $supplier = Supplier::All();
        $stok = StokBarang::All();
        $bm = BarangMasuk::All();
        
        $data = [
            'items' => $items,
            'supplier' => $supplier,
            'stok' => $stok,
            'bm' => $bm,
            'id' => $request->id,
            'nama' => $request->nama,
            'kode' => $request->kode,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.pembelian.ubahBM.detail', $data);
    }

    public function status(Request $request, $id) {
        $item = BarangMasuk::where('id', $id)->first();
        $item->{'status'} = 'PENDING_BATAL';
        $item->save();

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'PENDING_BATAL',
            'keterangan' => $request->input("ket".$id),
            'id_dokumen' => $id,
            'tipe' => 'Dokumen'
        ]);

        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');  
    }

    public function edit(Request $request, $id) {
        $items = DetilBM::with(['bm', 'barang'])->where('id_bm', $id)->get();
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $data = [
            'items' => $items,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.pembelian.ubahBM.edit', $data);
    }

    public function update(Request $request) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        $items = BarangMasuk::where('id', $request->kode)->first();
        $items->{'status'} = 'PENDING_UPDATE';
        $items->save();

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now(),
            'status' => 'PENDING_UPDATE',
            'keterangan' => $request->keterangan,
            'id_dokumen' => $request->kode,
            'tipe' => 'Dokumen'
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            NeedAppDetil::create([
                'id_app' => $newcode,
                'id_barang' => $request->kodeBarang[$i],
                'harga' => str_replace(".", "", $request->harga[$i]),
                'qty' => $request->qty[$i],
                'diskon' => NULL,
            ]);
        }

        $data = [
            'id' => $request->kode,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        $url = Route('bm-show', $data);
        return redirect($url);
    }
}
