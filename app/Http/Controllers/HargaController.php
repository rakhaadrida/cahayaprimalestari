<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Harga;

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
        return view('pages.harga.create');
    }

    public function store(Request $request)
    {
        $item = $request->all();
        Harga::create($item);

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

    public function update(Request $request, $id)
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
