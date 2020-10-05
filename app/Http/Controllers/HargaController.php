<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\RequestS\HargaRequest;
use App\Models\Harga;

class HargaController extends Controller
{
    public function index()
    {
        $items = Harga::All();
        $data = [
            'items' => $items
        ];

        return view('pages.harga.index', $data);
    }

    public function create()
    {
        $lastcode = Harga::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'HRG'.sprintf("%02s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];
        
        return view('pages.harga.create', $data);
    }

    public function store(HargaRequest $request)
    {
        Harga::create([
            'id' => $request->kode,
            'nama' => $request->nama
        ]);

        return redirect()->route('harga.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Harga::findOrFail($id);
        $data = [
            'item' => $item
        ];

        return view('pages.harga.edit', $data);
    }

    public function update(HargaRequest $request, $id)
    {
        $data = $request->all();
        $item = Harga::findOrFail($id);
        $item->update($data);

        return redirect()->route('harga.index');
    }

    public function destroy($id)
    {
        $item = Harga::findOrFail($id);
        $item->delete();

        return redirect()->route('harga.index');
    }
}
