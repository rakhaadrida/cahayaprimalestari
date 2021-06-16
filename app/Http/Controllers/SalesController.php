<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SalesRequest;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\BarangMasuk;
use App\Models\SalesOrder;
use App\Models\TransferBarang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use Carbon\Carbon;

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

        // $item = Customer::where('id_sales', $id)->get();
        // foreach($item as $i) {
        //     $i->id_sales = '';
        //     $i->save();
        // }

        return redirect()->route('sales.index');
    }

    public function trash() {
        $items = Sales::onlyTrashed()->get();
        $data = [
            'items' => $items
        ];

        return view('pages.sales.trash', $data);
    }

    public function restore($id) {
        $item = Sales::onlyTrashed()->where('id', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Sales::onlyTrashed();
        $items->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Sales::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Sales::onlyTrashed();
        $items->forceDelete();

        return redirect()->back();
    }

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new SalesExport(), 'Master Sales-'.$tglFile.'.xlsx');
    }
}
