<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gudang;

class GudangController extends Controller
{
    public function index()
    {
        $items = Gudang::All();
        $data = [
            'items' => $items
        ];

        return view('pages.gudang.index', $data);
    }

    public function create()
    {
        return view('pages.gudang.create');
    }

    public function store(Request $request)
    {
        $item = $request->all();
        Gudang::create($item);

        return redirect()->route('gudang.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Gudang::findOrFail($id);
        $data = [
            'item' => $item
        ];

        return view('pages.gudang.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $item = Gudang::findOrFail($id);
        $item->update($data);

        return redirect()->route('gudang.index');
    }

    public function destroy($id)
    {
        $item = Gudang::findOrFail($id);
        $item->delete();

        return redirect()->route('gudang.index');
    }
}
