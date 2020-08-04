<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;

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
        return view('pages.supplier.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        Supplier::create($data);
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

    public function update(Request $request, $id)
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
