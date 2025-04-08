<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Http\Requests\SalesRequest;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\Sales;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    public function index() {
        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $salesOrders = SalesOrder::query()
            ->whereBetween('tgl_so', ['2025-01-01', '2025-01-31'])
            ->get();
        // dd($salesOrders[0]->id);

        foreach($salesOrders as $salesOrder) {
            // dd(empty($salesOrder->ar));
            if(empty($salesOrder->ar)) {
                $lastcode = AccReceivable::join('so', 'so.id', 'ar.id_so')
                    ->selectRaw('max(ar.id) as id')->where('ar.id', 'LIKE', '%' . $tahun . $bulan . '%')->get();
                $lastnumber = (int)substr($lastcode[0]->id, 6, 4);
                $lastnumber++;
                $newcode = 'AR' . $tahun . $bulan . sprintf('%04s', $lastnumber);
                $arKode = $newcode;

                AccReceivable::create([
                    'id' => $newcode,
                    'id_so' => $salesOrder->id,
                    'keterangan' => 'LUNAS'
                ]);

                $lastcode = DetilAR::selectRaw('max(id_cicil) as id')->whereYear('created_at', $waktu->year)
                    ->whereMonth('created_at', $month)->get();
                $lastnumber = (int)substr($lastcode->first()->id, 7, 4);
                $lastnumber++;
                $newcode = 'CIC' . $tahun . $bulan . sprintf("%04s", $lastnumber);

                DetilAR::create([
                    'id_ar' => $arKode,
                    'id_cicil' => $newcode,
                    'tgl_bayar' => '2025-03-19',
                    'cicil' => $salesOrder->total
                ]);
            }
        }

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
