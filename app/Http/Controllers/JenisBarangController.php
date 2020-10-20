<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;

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

        return redirect()->route('jenis.index');
    }
}
