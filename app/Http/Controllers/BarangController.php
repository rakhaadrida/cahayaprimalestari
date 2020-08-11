<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Harga;
use App\HargaBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $items = Barang::All();
        $data =  [
            'items' => $items
        ];

        return view('pages.barang.index', $data);
    }

    public function create()
    {
        $lastcode = Barang::max('id');
        //$lastnumber = (int) substr($lastcode, 3, 2);
        $lastcode++;
        $newcode = 'BRG'.sprintf("%02s", $lastcode);

        $data = [
            'newcode' => $newcode
        ];
        
        return view('pages.barang.create', $data);
    }

    public function store(Request $request)
    {
        $item = $request->all();
        Barang::create($item);

        return redirect()->route('barang.index');
    }

    public function show($id)
    {
        
    }

    public function harga($id) 
    {
        $item = Barang::findOrFail($id);
        $harga = Harga::All();

        $data = [
            'item' => $item,
            'harga' => $harga
        ];

        return view('pages.barang.harga', $data);
    }

    public function storeHarga(Request $request)
    {
        $kode = $request->kode;
        $harga = Harga::select('id')->get();

        for($i=1; $i<=$harga->count(); $i++) {
            HargaBarang::create([
                'id_barang' => $kode,
                'id_harga' => $harga[$i-1]->id,
                'harga' => $request->$i,
            ]);
        }

        // foreach($harga->id as $h) {
        //     HargaBarang::create([
        //         'id_barang' => $kode,
        //         'id_harga' => $h,
        //         'harga' => $request->$h,
        //     ]);
        // }

        return redirect()->route('barang.index');
    }

    public function edit($id)
    {
        $item = Barang::findOrFail($id);
        $data = [
            'item' => $item
        ];
        
        return view('pages.barang.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        $item = Barang::findOrFail($id);
        $item->update($data);

        return redirect()->route('barang.index');
    }

    public function destroy($id)
    {
        $item = Barang::findOrFail($id);
        $item->delete();

        return redirect()->route('barang.index');
    }
}
