<?php

namespace App\Http\Controllers;

use App\Exports\GudangExport;
use App\Http\Requests\GudangRequest;
use App\Models\Gudang;
use App\Models\SalesOrder;
use App\Models\StokBarang;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class GudangController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::query()
            ->whereIn('status', ['INPUT', 'UPDATE', 'APPROVE_LIMIT'])
            ->where('tgl_so', '<', '2022-08-25')
            ->whereNull('deleted_at')
            ->get();

        foreach($salesOrders as $salesOrder) {
            $salesOrder->status = 'CETAK';
            $salesOrder->save();
        }

        $items = Gudang::All();

        $data = [
            'items' => $items
        ];

        return view('pages.gudang.index', $data);
    }

    public function create()
    {
        $lastcode = Gudang::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'GDG'.sprintf("%02s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];

        return view('pages.gudang.create', $data);
    }

    public function store(GudangRequest $request)
    {
        if($request->retur == '')
            $request->retur = 'F';

        Gudang::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'tipe' => $request->tipe
        ]);

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

    public function update(GudangRequest $request, $id)
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

        $item = StokBarang::where('id_gudang', $id);
        $item->delete();

        return redirect()->route('gudang.index');
    }

    public function trash() {
        $items = Gudang::onlyTrashed()->get();
        $data = [
            'items' => $items
        ];

        return view('pages.gudang.trash', $data);
    }

    public function restore($id) {
        $item = Gudang::onlyTrashed()->where('id', $id);
        $item->restore();

        $item = StokBarang::onlyTrashed()->where('id_gudang', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Gudang::onlyTrashed();
        $items->restore();

        $item = StokBarang::onlyTrashed();
        $item->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Gudang::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        $item = StokBarang::onlyTrashed()->where('id_gudang', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Gudang::onlyTrashed();
        $items->forceDelete();

        $item = StokBarang::onlyTrashed();
        $item->forceDelete();

        return redirect()->back();
    }

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new GudangExport(), 'Master Gudang-'.$tglFile.'.xlsx');
    }
}
