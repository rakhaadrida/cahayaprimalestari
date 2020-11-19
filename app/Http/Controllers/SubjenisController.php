<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subjenis;
use App\Models\JenisBarang;

class SubjenisController extends Controller
{
    public function index()
    {
        $items = Subjenis::All();
        $data = [
            'items' => $items
        ];

        return view('pages.subjenis.index', $data);
    }

    public function create()
    {
        $lastcode = Subjenis::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 3);
        $lastnumber++;
        $newcode = 'SUB'.sprintf("%03s", $lastnumber);
        $jenis = JenisBarang::All();

        $data = [
            'newcode' => $newcode,
            'jenis' => $jenis
        ];
        
        return view('pages.subjenis.create', $data);
    }

    public function store(Request $request)
    {
        Subjenis::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'id_kategori' => $request->kodeJenis
        ]);

        return redirect()->route('subjenis.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Subjenis::findOrFail($id);
        $jenis = JenisBarang::All();

        $data = [
            'item' => $item,
            'jenis' => $jenis
        ];

        return view('pages.subjenis.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $item = Subjenis::where('id', $id)->first();
        $item->{'nama'} = $request->nama;
        $item->{'id_kategori'} = $request->kodeJenis;
        $item->save();

        return redirect()->route('subjenis.index');
    }

    public function destroy($id)
    {
        $item = Subjenis::findOrFail($id);
        $item->delete();

        return redirect()->route('subjenis.index');
    }

    public function trash() {
        $items = Subjenis::onlyTrashed()->get();

        $data = [
            'items' => $items,
        ];

        return view('pages.subjenis.trash', $data);
    }

    public function restore($id) {
        $item = Subjenis::onlyTrashed()->where('id', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Subjenis::onlyTrashed();
        $items->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Subjenis::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Subjenis::onlyTrashed();
        $items->forceDelete();

        return redirect()->back();
    }
}
