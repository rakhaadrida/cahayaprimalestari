<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SalesRequest;
use App\Models\Sales;

class SalesController extends Controller
{
    public function index() {
        $items = Sales::All();
        $data = [
            'items' => $items
        ];

        return view('pages.sales.index', $data);
    }

    public function create() {
        $lastcode = Sales::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'SLS'.sprintf("%02s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];

        return view('pages.sales.create', $data);
    }

    public function store(SalesRequest $request) {
        Sales::create([
            'id' => $request->kode,
            'nama' => $request->nama
        ]);

        return redirect()->route('sales.index');
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $item = Sales::findOrFail($id);
        $data = [
            'item' => $item
        ];

        return view('pages.sales.edit', $data);
    }

    public function update(SalesRequest $request, $id) {
        $data = $request->all();
        $item = Sales::findOrFail($id);
        $item->update($data);

        return redirect()->route('sales.index');
    }

    public function destroy($id) {
        $item = Sales::findOrFail($id);
        $item->delete();

        return redirect()->route('sales.index');
    }
}
