<?php

namespace App\Http\Controllers;

use App\Exports\BarangExport;
use App\Http\Requests\BarangRequest;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Harga;
use App\Models\HargaBarang;
use App\Models\JenisBarang;
use App\Models\StokBarang;
use App\Models\Subjenis;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index() {
        $itemsBrg = Barang::All();
        $itemsBrgOff = Barang::whereIn('id_kategori', ['KAT03', 'KAT08'])->get();
        $gudang = Gudang::where('id', 'GDG06');

        if(Auth::user()->is_admin) {
            $gudang = $gudang->orWhere('id', 'GDG02');
        } else {
            $gudang = $gudang->orWhere('id', 'GDG08');
        }

        $gudang = $gudang->get();

        $data =  [
            'itemsBrg' => $itemsBrg,
            'itemsBrgOff' => $itemsBrgOff,
            'gudang' => $gudang,
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
        $harga = Harga::All();

        $data = [
            'newcode' => $newcode,
            'jenis' => $jenis,
            'subjenis' => $subjenis,
            'harga' => $harga
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
            'ukuran' => $request->ukuran
        ]);

        $gudang = Gudang::All();
        foreach($gudang as $g) {
            StokBarang::create([
                'id_barang' => $request->kode,
                'id_gudang' => $g->id,
                'status' => 'T',
                'stok' => 0
            ]);

            if($g->tipe == 'RETUR') {
                StokBarang::create([
                    'id_barang' => $request->kode,
                    'id_gudang' => $g->id,
                    'status' => 'F',
                    'stok' => 0
                ]);
            }
        }

        $harga = Harga::All();
        for($i = 0; $i < $harga->count(); $i++) {
            HargaBarang::create([
                'id_barang' => $request->kode,
                'id_harga' => $request->kodeHarga[$i],
                'harga' => str_replace(".", "", $request->harga[$i]),
                'ppn' => str_replace(".", "", $request->ppn[$i]),
                'harga_ppn' => str_replace(".", "", $request->hargaPPN[$i])
            ]);
            }

        return redirect()->route('barang.index');
    }

    public function show($id) {

    }

    public function detail($id) {
        $item = Barang::select('barang.*', 'jenisbarang.nama AS namaJenis', 'subjenis.nama AS namaSub')
            ->leftJoin('jenisbarang', 'jenisbarang.id', 'barang.id_kategori')
            ->leftJoin('subjenis', 'subjenis.id', 'barang.id_sub')
            ->where('barang.id', $id)
            ->get();

        $hargaBarang = HargaBarang::where('id_barang', $id)->get();
        $harga = Harga::All();
        $gudang = Gudang::All();

        $data = [
            'item' => $item,
            'hargaBarang' => $hargaBarang,
            'harga' => $harga,
            'gudang' => $gudang
        ];

        return view('pages.barang.detail', $data);
    }

    public function harga($id) {
        $items = HargaBarang::where('id_barang', $id)->get();
        $harga = Harga::All();
        $barang = Barang::where('id', $id)->first();

        $data = [
            'items' => $items,
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
        for($i = 0; $i < $harga->count(); $i++) {
            if($items->count() == $harga->count()) {
                $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
            else if(($items->count() > 0) && ($j < $items->count())) {
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
        $items = StokBarang::where('id_barang', $id)->get();
        $gudang = Gudang::All();
        $barang = Barang::where('id', $id)->first();

        $data = [
            'items' => $items,
            'gudang' => $gudang,
            'barang' => $barang
        ];

        return view('pages.barang.stok', $data);
    }

    public function storeStok(BarangRequest $request) {
        $kode = $request->kode;
        $items = StokBarang::where('id_barang', $kode)->where('status', 'T')->get();
        $itemsRow = StokBarang::where('id_barang', $kode)->count();
        $gudang = Gudang::All();

        $j = 0;
        for($i = 0; $i < $gudang->count(); $i++) {
            if($items->count() == $gudang->count()) {
                $this->updateStok($kode, $gudang[$i]->id, $request->stok[$i]);
            }
            else if(($items->count() > 0) && ($j < $items->count())) {
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
            'status' => 'T',
            'stok' => $stok,
        ]);
    }

    public function updateStok($kode, $id, $stok) {
        $updateStok = StokBarang::where('id_barang', $kode)->where('id_gudang', $id)->first();
        $updateStok->{'stok'} = $stok;
        $updateStok->save();
    }

    public function edit($id) {
        $item = Barang::select('barang.*', 'jenisbarang.nama AS namaJenis', 'subjenis.nama AS namaSub')
            ->leftJoin('jenisbarang', 'jenisbarang.id', 'barang.id_kategori')
            ->leftJoin('subjenis', 'subjenis.id', 'barang.id_sub')
            ->findOrFail($id);

        $jenis = JenisBarang::All();
        $subjenis = Subjenis::All();
        $harga = Harga::All();
        $items = HargaBarang::where('id_barang', $id)->get();

        $data = [
            'item' => $item,
            'jenis' => $jenis,
            'subjenis' => $subjenis,
            'harga' => $harga,
            'items' => $items
        ];

        return view('pages.barang.edit', $data);
    }

    public function update(BarangRequest $request, $id) {
        $item = Barang::where('id', $id)->first();
        $item->{'nama'} = $request->nama;
        $item->{'id_kategori'} = $request->kodeJenis;
        $item->{'id_sub'} = $request->kodeSub;
        $item->{'satuan'} = $request->satuan;
        $item->{'ukuran'} = $request->ukuran;
        $item->save();

        $kode = $id;
        $items = HargaBarang::where('id_barang', $kode)->get();
        $itemsRow = HargaBarang::where('id_barang', $kode)->count();
        $harga = Harga::All();

        $j = 0;
        for($i = 0; $i < $harga->count(); $i++) {
            if($items->count() == $harga->count()) {
                $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
            else if(($items->count() > 0) && ($j < $items->count())) {
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

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new BarangExport(), 'Master Barang-'.$tglFile.'.xlsx');
    }
}
