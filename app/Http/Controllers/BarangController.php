<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Subjenis;
use App\Models\Harga;
use App\Models\Gudang;
use App\Models\HargaBarang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use App\Http\Requests\BarangRequest;

class BarangController extends Controller
{
    public function index() {
        $items = Barang::All();
        $gudang = Gudang::All();
        $stok = StokBarang::All();
        $harga = Harga::All();
        $hargaBarang = HargaBarang::All();
        // $items = Barang::with(['hargaBarang', 'stokBarang'])->get();
        $data =  [
            'items' => $items,
            'gudang' => $gudang,
            'stok' => $stok,
            'harga' => $harga,
            'hargaBarang' => $hargaBarang
        ];

        return view('pages.barang.index', $data);
    }

    public function create() {
        $lastcode = Barang::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'BRG'.sprintf("%04s", $lastnumber);
        $jenis = JenisBarang::All();
        $subjenis = Subjenis::All();

        $data = [
            'newcode' => $newcode,
            'jenis' => $jenis,
            'subjenis' => $subjenis
        ];
        
        return view('pages.barang.create', $data);
    }

    public function store(BarangRequest $request) {
        Barang::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'id_kategori' => $request->kodeJenis,
            'id_sub' => $request->kodeSub,
            'satuan' => $request->satuan,
            'ukuran' => str_replace(".", "", $request->ukuran)
        ]);

        return redirect()->route('barang.index');
    }

    public function show($id) {
        
    }

    public function harga($id) {
        $items = HargaBarang::with(['hargaBarang', 'barang'])->where('id_barang', $id)->get();
        $itemsRow = HargaBarang::where('id_barang', $id)->count();
        $harga = Harga::All();
        $barang = Barang::where('id', $id)->first();

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'harga' => $harga,
            'barang' => $barang
        ];

        return view('pages.barang.harga', $data);
    }

    public function storeHarga(BarangRequest $request) {
        $kode = $request->kode;
        $items = HargaBarang::where('id_barang', $kode)->get();
        $itemsRow = HargaBarang::where('id_barang', $kode)->count();
        $harga = Harga::All();

        $j = 0;
        for($i=0; $i < $harga->count(); $i++) {
            if($itemsRow == $harga->count()) {
                $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
            else if(($itemsRow > 0) && ($j < $itemsRow)) {
                if($items[$j]->id_harga == $harga[$i]->id) {
                    $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i], 
                    $request->ppn[$i], $request->hargaPPN[$i]);
                    $j++;
                }
                else {
                    $this->createHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
                }
            }
            else {
                $this->createHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
        }

        return redirect()->route('barang.index');
    }

    public function createHarga($kode, $id, $harga, $ppn, $hargaPPN) {
        HargaBarang::create([
            'id_barang' => $kode,
            'id_harga' => $id,
            'harga' => str_replace(".", "", $harga),
            'ppn' => str_replace(".", "", $ppn),
            'harga_ppn' => str_replace(".", "", $hargaPPN)
        ]);
    }

    public function updateHarga($kode, $id, $harga, $ppn, $hargaPPN) {
        $updateHarga = HargaBarang::where('id_barang', $kode)->where('id_harga', $id)->first();
        $updateHarga->{'harga'} = str_replace(".", "", $harga);
        $updateHarga->{'ppn'} = str_replace(".", "", $ppn);
        $updateHarga->{'harga_ppn'} = str_replace(".", "", $hargaPPN);
        $updateHarga->save();
    }

    public function stok($id) {
        $items = StokBarang::with(['gudang', 'barang'])->where('id_barang', $id)->get();
        $itemsRow = StokBarang::where('id_barang', $id)->count();
        $gudang = Gudang::All();
        $barang = Barang::where('id', $id)->first();

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'gudang' => $gudang,
            'barang' => $barang
        ];

        return view('pages.barang.stok', $data);
    }

    public function storeStok(BarangRequest $request) {
        $kode = $request->kode;
        $items = StokBarang::where('id_barang', $kode)->get();
        $itemsRow = StokBarang::where('id_barang', $kode)->count();
        $gudang = Gudang::All();

        $j = 0;
        for($i = 0; $i < $gudang->count(); $i++) {
            if($itemsRow == $gudang->count()) {
                $this->updateStok($kode, $gudang[$i]->id, $request->stok[$i]);
            }
            else if(($itemsRow > 0) && ($j < $itemsRow)) {
                if($items[$j]->id_gudang == $gudang[$i]->id) {
                    $this->updateStok($kode, $gudang[$i]->id, $request->stok[$i]);
                    $j++;
                }
                else {
                    $this->createStok($kode, $gudang[$i]->id, $request->stok[$i]);
                }
            }
            else {
                $this->createStok($kode, $gudang[$i]->id, $request->stok[$i]);
            }
        }

        return redirect()->route('barang.index');
    }

    public function createStok($kode, $id, $stok) {
        StokBarang::create([
            'id_barang' => $kode,
            'id_gudang' => $id,
            'stok' => $stok,
            'status' => 'T'
        ]);
    }

    public function updateStok($kode, $id, $stok) {
        $updateStok = StokBarang::where('id_barang', $kode)->where('id_gudang', $id)->first();
        $updateStok->{'stok'} = $stok;
        $updateStok->save();
    }

    public function edit($id) {
        $item = Barang::with(['jenis'])->findOrFail($id);
        $jenis = JenisBarang::All();
        $subjenis = Subjenis::All();

        $data = [
            'item' => $item,
            'jenis' => $jenis,
            'subjenis' => $subjenis
        ];
        
        return view('pages.barang.edit', $data);
    }

    public function update(BarangRequest $request, $id) {
        $item = Barang::where('id', $id)->first();
        $item->{'nama'} = $request->nama;
        $item->{'id_kategori'} = $request->kodeJenis;
        $item->{'id_sub'} = $request->kodeSub;
        $item->{'satuan'} = $request->satuan;
        $item->{'ukuran'} = str_replace(".", "", $request->ukuran);
        $item->save();

        return redirect()->route('barang.index');
    }

    public function destroy($id) {
        $item = Barang::findOrFail($id);
        $item->delete();

        $items = StokBarang::where('id_barang', $id);
        $items->delete();

        $item = HargaBarang::where('id_barang', $id);
        $item->delete();

        return redirect()->route('barang.index');
    }

    public function trash() {
        $items = Barang::onlyTrashed()->get();
        $gudang = Gudang::All();
        $stok = StokBarang::onlyTrashed()->get();
        $harga = Harga::All();
        $hargaBarang = HargaBarang::onlyTrashed()->get();

        $data = [
            'items' => $items,
            'gudang' => $gudang,
            'stok' => $stok,
            'harga' => $harga,
            'hargaBarang' => $hargaBarang
        ];

        return view('pages.barang.trash', $data);
    }

    public function restore($id) {
        $item = Barang::onlyTrashed()->where('id', $id);
        $item->restore();

        $items = StokBarang::onlyTrashed()->where('id_barang', $id);
        $items->restore();

        $item = HargaBarang::onlyTrashed()->where('id_barang', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Barang::onlyTrashed();
        $items->restore();

        $items = StokBarang::onlyTrashed();
        $items->restore();

        $item = HargaBarang::onlyTrashed();
        $item->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Barang::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        $items = StokBarang::onlyTrashed()->where('id_barang', $id);
        $items->forceDelete();

        $item = HargaBarang::onlyTrashed()->where('id_barang', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Barang::onlyTrashed();
        $items->forceDelete();

        $items = StokBarang::onlyTrashed();
        $items->forceDelete();

        $item = HargaBarang::onlyTrashed();
        $item->forceDelete();

        return redirect()->back();
    }
}
