<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Harga;
use App\Gudang;
use App\HargaBarang;
use App\StokBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $items = Barang::All();
        $gudang = Gudang::All();
        $stok = stokBarang::All();
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

    public function create()
    {
        $lastcode = Barang::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 3);
        $lastnumber++;
        $newcode = 'BRG'.sprintf("%03s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];
        
        return view('pages.barang.create', $data);
    }

    public function store(Request $request) {
        Barang::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'ukuran' => $request->ukuran,
            'isi' => $request->isi
        ]);

        return redirect()->route('barang.index');
    }

    public function show($id)
    {
        
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

    public function storeHarga(Request $request) {
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
            'harga' => $harga,
            'ppn' => $ppn,
            'harga_ppn' => $hargaPPN
        ]);
    }

    public function updateHarga($kode, $id, $harga, $ppn, $hargaPPN) {
        $updateHarga = HargaBarang::where('id_barang', $kode)->where('id_harga', $id)->first();
        $updateHarga->{'harga'} = $harga;
        $updateHarga->{'ppn'} = $ppn;
        $updateHarga->{'harga_ppn'} = $hargaPPN;
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

    public function storeStok(Request $request) {
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
            'stok' => $stok
        ]);
    }

    public function updateStok($kode, $id, $stok) {
        $updateStok = StokBarang::where('id_barang', $kode)->where('id_gudang', $id)->first();
        $updateStok->{'stok'} = $stok;
        $updateStok->save();
    }

    public function edit($id) {
        $item = Barang::findOrFail($id);
        $data = [
            'item' => $item
        ];
        
        return view('pages.barang.edit', $data);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        
        $item = Barang::findOrFail($id);
        $item->update($data);

        return redirect()->route('barang.index');
    }

    public function destroy($id) {
        $item = Barang::findOrFail($id);
        $item->delete();

        return redirect()->route('barang.index');
    }
}
