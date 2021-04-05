<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\RequestS\HargaRequest;
use App\Models\Harga;
use App\Models\HargaBarang;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\AR_Retur;

class HargaController extends Controller
{
    public function index()
    {   
        $items = Harga::All();
        $data = [
            'items' => $items
        ];

        $kodeCicil = ['AR21020493', 'AR21020438', 'AR21020875', 'AR21020997', 'AR21020409', 'AR21020763', 'AR21020894',
                    'AR21020287', 'AR21020580', 'AR21020817'];
        $totCicil = [312821460, 362952, 3711456, 897169, 2055876, 2974590, 1142127, 3159216, 1207710, 18705603];

        for($i = 0; $i < sizeof($kodeCicil); $i++) {
            $item = DetilAR::where('id_ar', $kodeCicil[$i])->first();
            $item->{'cicil'} = $totCicil[$i];
            $item->save();
        }

        $items = AccReceivable::join('so', 'so.id', 'ar.id_so')
                ->select('ar.id as id', 'total')->where('keterangan', 'LUNAS')->get();
        $no = 1;
        foreach($items as $i) {
            $det = DetilAR::where('id_ar', $i->id)->first();
            $detil = DetilAR::selectRaw('sum(cicil) as cicil')->where('id_ar', $i->id)->get();
            $retur = AR_Retur::selectRaw('sum(total) as total')->where('id_ar', $i->id)->get();
            if($det == NULL) {
                DetilAR::create([
                    'id_ar' => $i->id,
                    'id_cicil' => 'CIC2000000'.$no,
                    'tgl_bayar' => '2021-02-28',
                    'cicil' => $i->total - $retur->first()->total
                ]);
                $no++;
            } else {
                if($i->total != ($detil->first()->cicil + $retur->first()->total)) {
                    if($i->total > ($detil->first()->cicil + $retur->first()->total)) 
                        $total = $detil->first()->cicil + ($i->total - $detil->first()->cicil - $retur->first()->total);
                    else
                        $total = $detil->first()->cicil - ($detil->first()->cicil - $retur->first()->total - $i->total);

                    $det->{'cicil'} = $total;
                    $det->save();
                }
            }
        }

        $cicil = ['CIC20120014', 'CIC20120015'];
        $items = DetilAR::whereIn('id_cicil', $cicil)->delete();
        $item = DetilAR::where('id_cicil', 'CIC21020001')->where('id_ar', 'AR20000086')->delete();

        return view('pages.harga.index', $data);
    }

    public function create()
    {
        $lastcode = Harga::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'HRG'.sprintf("%02s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];
        
        return view('pages.harga.create', $data);
    }

    public function store(HargaRequest $request)
    {
        Harga::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'tipe' => $request->tipe
        ]);

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

    public function update(HargaRequest $request, $id)
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

        $item = HargaBarang::where('id_harga', $id);
        $item->delete();

        return redirect()->route('harga.index');
    }

    public function trash() {
        $items = Harga::onlyTrashed()->get();
        $data = [
            'items' => $items
        ];

        return view('pages.harga.trash', $data);
    }

    public function restore($id) {
        $item = Harga::onlyTrashed()->where('id', $id);
        $item->restore();

        $item = HargaBarang::onlyTrashed()->where('id_harga', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Harga::onlyTrashed();
        $items->restore();

        $item = HargaBarang::onlyTrashed();
        $item->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Harga::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        $item = HargaBarang::onlyTrashed()->where('id_harga', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Harga::onlyTrashed();
        $items->forceDelete();

        $item = HargaBarang::onlyTrashed();
        $item->forceDelete();

        return redirect()->back();
    }
}
