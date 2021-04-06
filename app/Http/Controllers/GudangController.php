<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GudangRequest;
use App\Models\Gudang;
use App\Models\StokBarang;
use App\Models\DetilAR;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GudangExport;
use Carbon\Carbon;

class GudangController extends Controller
{
    public function index()
    {   
        $items = Gudang::All();
        $data = [
            'items' => $items
        ];

        /* $kode = ['AR21031449', 'AR21030940', 'AR21030849', 'AR21031091', 'AR21030233'];
        $cicil = [5228873, 857610, 386835, 203202, 138165];
        $items = DetilAR::where('id_cicil', 'CIC21000001')->get();

        if($items->count() == 0) {
            for($i = 1; $i <= sizeof($kode); $i++) {
                DetilAR::create([
                    'id_ar' => $kode[$i-1],
                    'id_cicil' => 'CIC2100000'.$i,
                    'tgl_bayar' => '2021-04-01',
                    'cicil' => $cicil[$i-1]
                ]);
            }   
        }

        $kodeCicil = ['AR21030430', 'AR21030434', 'AR21030261', 'AR21030131'];
        $totCicil = [0, 2767620, 7052092, 1707952];
        
        for($i = 0; $i < sizeof($kodeCicil); $i++) {
            $item = DetilAR::where('id_ar', $kodeCicil[$i])->first();
            $item->{'cicil'} = $totCicil[$i];
            $item->save();
        } */

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
