<?php

namespace App\Http\Controllers;

use App\Barang;
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
        return view('pages.barang.create');
    }

    public function store(Request $request)
    {
        $item = $request->all();
        Barang::create($item);

        return redirect()->route('barang.index');
    }

    public function show(Barang $barang)
    {
        //
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
