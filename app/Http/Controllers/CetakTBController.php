<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferBarang;
use Carbon\Carbon;

class CetakTBController extends Controller
{
    public function index($status, $awal, $akhir) {
        $items = TransferBarang::where('status', 'INPUT')->orderBy('tgl_tb', 'asc')->get();
        $data = [
            'items' => $items,
            'status' => $status,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.pembelian.cetakTB.index', $data);
    }

    public function process(Request $request) {
        $data = [
            'awal' => $request->kodeAwal,
            'akhir' => $request->kodeAkhir,
            'status' => 'true'
        ];

        // return redirect()->route('cetak-tb', $data);
        return redirect()->route('cetak-tb-all', ['awal' => $request->kodeAwal, 'akhir' => $request->kodeAkhir]);
    }

    public function cetak($awal, $akhir) {
        $items = TransferBarang::where('status', 'INPUT')->whereBetween('id', [$awal, $akhir])
                ->orderBy('tgl_tb', 'asc')->get();

        foreach($items as $i) {
            $item = TransferBarang::where('id', $i->id)->get();
            $tabel = ceil($item->first()->detiltb->count() / 12);

            if($tabel > 1) {
                for($j = 1; $j < $tabel; $j++) {
                    $newItem = collect([
                        'id' => $item->first()->id.'Z',
                        'tgl_tb' => $item->first()->tgl_tb,
                        'status' => $items->first()->status,
                        'id_user' => $items->first()->id_user,
                    ]);

                    $items->push($newItem);
                }
            }
        }   

        $items = $items->sortBy(function ($product, $key) {
                    return $product['tgl_tb'].$product['id'];
                });
        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.pembelian.cetakTB.cetakInv', $data);
    } 

    public function update($awal, $akhir) {
        $items = TransferBarang::whereBetween('id', [$awal, $akhir])->get();

        foreach($items as $item) {
            $item->status = 'CETAK';
            $item->save();
        }

        $data = [
            'status' => 'false',
            'awal' => 0,
            'akhir' => 0
        ];

        return redirect()->route('cetak-tb', $data);
    }
}
