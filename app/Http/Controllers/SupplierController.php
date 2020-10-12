<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $items = Supplier::All();

        $data = [
            'items' => $items
        ];
        
        return view('pages.supplier.index', $data);
    }

    public function create()
    {
        $lastcode = Supplier::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 3);
        $lastnumber++;
        $newcode = 'SUP'.sprintf("%03s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];

        return view('pages.supplier.create', $data);
    }

    public function store(SupplierRequest $request)
    {
        Supplier::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'npwp' => $request->npwp
        ]);
        
        return redirect()->route('supplier.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Supplier::findOrFail($id);

        $data = [
            'item' => $item
        ];

        return view('pages.supplier.edit', $data);
    }

    public function update(SupplierRequest $request, $id)
    {
        $data = $request->all();

        $item = Supplier::findOrFail($id);
        $item->update($data);

        return redirect()->route('supplier.index');
    }

    public function destroy($id)
    {
        $item = Supplier::findOrFail($id);
        $item->delete();

        return redirect()->route('supplier.index');
    }
}
