<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;
use App\DetilSO;

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
}
