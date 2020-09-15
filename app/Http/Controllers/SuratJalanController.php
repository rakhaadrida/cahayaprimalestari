<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;
use App\DetilSO;
use App\SuratJalan;
use App\DetilSJ;
use App\StokBarang;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    public function index() {
        $SalesOrder = SalesOrder::All();

        // date now
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'salesOrder' => $SalesOrder,
            'tanggal' => $tanggal
        ];

        return view('pages.penjualan.suratJalan', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function show(Request $request) {
        $SalesOrder = SalesOrder::All();
        $items = DetilSO::with(['so', 'barang'])->where('id_so', $request->kode)->get();
        $itemsRow = DetilSO::where('id_so', $request->kode)->count();
        $tanggal = $request->tanggal;

        $data = [
            'salesOrder' => $SalesOrder,
            'items' => $items,
            'itemsRow' => $itemsRow,
            'tanggal' => $tanggal
        ];

        return view('pages.penjualan.detilSJ', $data);
    }

    public function process(Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');

        SuratJalan::create([
            'id_so' => $id,
            'tgl_sj' => $tanggal,
            'keterangan' => $request->keterangan
        ]);

        $i = 0;
        $tempDetil = DetilSO::where('id_so', $id)->get();
        foreach($tempDetil as $td) {
            if($request->qtyRevisi[$i] != null) {
                DetilSJ::create([
                    'id_so' => $id,
                    'id_barang' => $td->id_barang,
                    'qtyRevisi' => $request->qtyRevisi[$i],
                    'keterangan' => $request->detailKet[$i],
                ]);
            }

            $updateStok = StokBarang::where('id_barang', $td->id_barang)
                            ->where('id_gudang', 'GDG01')->first();
            if($td->qty > $request->qtyRevisi[$i]) {
                $updateStok->{'stok'} += ($td->qty - $request->qtyRevisi[$i]);
            }
            else {
                $updateStok->{'stok'} -= ($request->qtyRevisi[$i] - $td->qty);
            }
            $updateStok->save();
            $i++;
        }

        return redirect()->route('sj');
    }
}
