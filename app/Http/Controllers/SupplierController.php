<?php

namespace App\Http\Controllers;

use App\Exports\SupplierExport;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'SUP'.sprintf("%02s", $lastnumber);

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

    public function trash() {
        $items = Supplier::onlyTrashed()->get();
        $data = [
            'items' => $items
        ];

        return view('pages.supplier.trash', $data);
    }

    public function restore($id) {
        $item = Supplier::onlyTrashed()->where('id', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Supplier::onlyTrashed();
        $items->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Supplier::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Supplier::onlyTrashed();
        $items->forceDelete();

        return redirect()->back();
    }

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new SupplierExport(), 'Master Supplier-'.$tglFile.'.xlsx');
    }
}
