<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;
use App\Models\Subjenis;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JenisExport;
use Carbon\Carbon;

class JenisBarangController extends Controller
{
    public function index() {
        $items = JenisBarang::All();
        $data = [
            'items' => $items
        ];

        return view('pages.jenisbarang.index', $data);
    }

    public function create() {
        $lastcode = JenisBarang::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'KAT'.sprintf("%02s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];
        
        return view('pages.jenisbarang.create', $data);
    }

    public function store(Request $request) {
        JenisBarang::create([
            'id' => $request->kode,
            'nama' => $request->nama
        ]);

        return redirect()->route('jenis.index');
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $item = JenisBarang::findOrFail($id);
        $data = [
            'item' => $item
        ];

        return view('pages.jenisbarang.edit', $data);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $item = JenisBarang::findOrFail($id);
        $item->update($data);

        return redirect()->route('jenis.index');
    }

    public function destroy($id) {
        $item = JenisBarang::findOrFail($id);
        $item->delete();

        $item = Barang::where('id_kategori', $id)->get();
        foreach($item as $i) {
            $i->id_kategori = '';
            $i->save();
        }

        $item = Subjenis::where('id_kategori', $id)->get();
        foreach($item as $i) {
            $i->id_kategori = '';
            $i->save();
        }

        return redirect()->route('jenis.index');
    }

    public function trash() {
        $items = JenisBarang::onlyTrashed()->get();
        $data = [
            'items' => $items
        ];

        return view('pages.jenisbarang.trash', $data);
    }

    public function restore($id) {
        $item = JenisBarang::onlyTrashed()->where('id', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = JenisBarang::onlyTrashed();
        $items->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = JenisBarang::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = JenisBarang::onlyTrashed();
        $items->forceDelete();

        return redirect()->back();
    }

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new JenisExport(), 'Master JenisBarang-'.$tglFile.'.xlsx');
    }
}
